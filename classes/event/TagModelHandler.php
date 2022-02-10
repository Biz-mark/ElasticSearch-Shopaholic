<?php namespace BizMark\ElasticSearchShopaholic\Classes\Event;

use System\Classes\PluginManager;
use BizMark\ElasticSearchShopaholic\Classes\Helper\SearchHelper;

/**
 * Class TagModelHandler
 * @package BizMark\ElasticSearchShopaholic\Classes\Event
 * @author Nick Khaetsky, nick@biz-mark.ru, Biz-Mark
 */
class TagModelHandler extends AbstractSearchModelHandler
{
    /** @var \Lovata\TagsShopaholic\Models\Tag */
    protected $obElement;

    /** @var string */
    protected $sSearchModel = 'tag';

    protected function extendItemCollection()
    {
        \Lovata\TagsShopaholic\Classes\Collection\TagCollection::extend(function ($obCollection) {
            /** @var \Lovata\TagsShopaholic\Classes\Collection\TagCollection $obCollection */
            $obCollection->addDynamicMethod('search', function ($sSearch) use ($obCollection) {
                $arResult = $this->obElasticSearch->search([
                    'index' => config('bizmark.elasticsearchshopaholic::'.$this->sSearchModel.'.index'),
                    'type' => config('bizmark.elasticsearchshopaholic::'.$this->sSearchModel.'.type'),
                    'body' => [
                        '_source' => false,
                        'query' => [
                            'query_string' => [
                                'query' => '*'.$sSearch.'*',
                            ],
                        ]
                    ]
                ]);

                if (empty($arResult)) {
                    return $obCollection;
                }

                $arElementIDList = $this->retrieveIds($arResult);

                return $obCollection->applySorting($arElementIDList);
            });
        });
    }

    protected function getSearchBody($obElement)
    {
        return [
            'name' => $obElement->name,
            'code' => $obElement->code,
            'preview_text' => $obElement->preview_text,
            'description' => $obElement->description,
            'search_synonym' => $obElement->search_synonym,
            'search_content' => $obElement->search_content
        ];
    }

    protected function getModelClass()
    {
        return \Lovata\TagsShopaholic\Models\Tag::class;
    }

    protected function getItemClass()
    {
        return \Lovata\TagsShopaholic\Classes\Item\TagItem::class;
    }
}
