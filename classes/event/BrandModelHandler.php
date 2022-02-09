<?php namespace BizMark\ElasticSearchShopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Classes\Collection\BrandCollection;

/**
 * Class BrandModelHandler
 * @package BizMark\ElasticSearchShopaholic\Classes\Event
 * @author Nick Khaetsky, nick@biz-mark.ru, Biz-Mark
 */
class BrandModelHandler extends AbstractSearchModelHandler
{
    /** @var Brand */
    protected $obElement;

    /** @var string */
    protected $sSearchModel = 'brand';

    protected function extendItemCollection()
    {
        BrandCollection::extend(function ($obCollection) {
            /** @var BrandCollection $obCollection */
            $obCollection->addDynamicMethod('search', function ($sSearch) use ($obCollection) {
//                $obSearchHelper = app(SearchHelper::class, ['product']);
//                $arElementIDList = $obSearchHelper->result($sSearch);
//                return $obCollection->applySorting($arElementIDList);
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
        return Brand::class;
    }

    protected function getItemClass()
    {
        return BrandItem::class;
    }
}
