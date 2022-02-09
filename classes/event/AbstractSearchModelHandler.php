<?php namespace BizMark\ElasticSearchShopaholic\Classes\Event;

use Elasticsearch\Client;
use October\Rain\Support\Arr;

/**
 * Class AbstractSearchModelHandler
 * @package BizMark\ElasticSearchShopaholic\Classes\Event
 * @author Nick Khaetsky, nick@biz-mark.ru, Biz-Mark
 */
abstract class AbstractSearchModelHandler
{
    /** @var \Model */
    protected $obElement;

    /** @var string */
    protected $sSearchModel;

    /** @var \Elasticsearch\Client */
    protected $obElasticSearch;

    /** @var string  */
    protected $sIdentifierField = 'id';

    /**
     * Add listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function subscribe($obEvent)
    {
        try {
            $this->initElasticSearch();
            $sModelClass = $this->getModelClass();

            $sModelClass::extend(function ($obElement) {
                $this->addFields($obElement);
                $this->addDynamicMethods($obElement);

                $obElement->bindEvent('model.afterSave', function () use ($obElement) {
                    $this->obElement = $obElement;
                    $this->afterSave();
                });

                $obElement->bindEvent('model.afterDelete', function () use ($obElement) {
                    $this->obElement = $obElement;
                    $this->afterDelete();
                });
            });

            $this->extendItemCollection();
        } catch (\Exception $ex) {
            trace_log($ex);
        }
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function initElasticSearch()
    {
        $this->obElasticSearch = app()->make(Client::class);
    }

    protected function addFields($obElement)
    {
        $obElement->fillable[] = 'search_synonym';
        $obElement->fillable[] = 'search_content';
    }

    protected function addDynamicMethods($obElement)
    {
        $obElement->addDynamicMethod('getSearchBody', function () use ($obElement){
            return $this->getSearchBody($obElement);
        });
    }

    protected function afterSave()
    {
        try {
            $this->obElasticSearch->index([
                'index' => config('bizmark.elasticsearchshopaholic::'.$this->sSearchModel.'.index'),
                'id' => config('bizmark.elasticsearchshopaholic::'.$this->sSearchModel.'.key'),
                'body' => $this->obElement->getSearchBody()
            ]);

            $this->clearItemCache();
        } catch (\Exception $ex) {
            trace_log($ex);
        }
    }

    protected function afterDelete()
    {
        try {
            $this->obElasticSearch->delete([
                'index' => config('bizmark.elasticsearchshopaholic::'.$this->sSearchModel.'.index'),
                'id' => config('bizmark.elasticsearchshopaholic::'.$this->sSearchModel.'.key'),
            ]);

            $this->clearItemCache();
        } catch (\Exception $ex) {
            trace_log($ex);
        }
    }

    protected function clearItemCache()
    {
        $sItemClass = $this->getItemClass();
        $sField = $this->sIdentifierField;

        $sItemClass::clearCache($this->obElement->$sField);
    }

    protected function retrieveIds($arResult)
    {
        $arHits = Arr::get($arResult, 'hits.hits');
        $arIds = [];
        foreach ($arHits as $arHit) {
            $arIds[] = $arHit['_id'];
        }

        return $arIds;
    }

    abstract protected function extendItemCollection();

    abstract protected function getSearchBody($obElement);

    abstract protected function getModelClass();

    abstract protected function getItemClass();
}
