<?php namespace BizMark\ElasticSearchShopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Collection\CategoryCollection;
use Lovata\Shopaholic\Classes\Item\BrandItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;

/**
 * Class CategoryModelHandler
 * @package BizMark\ElasticSearchShopaholic\Classes\Event
 * @author Nick Khaetsky, nick@biz-mark.ru, Biz-Mark
 */
class CategoryModelHandler extends AbstractSearchModelHandler
{
    /** @var Category */
    protected $obElement;

    /** @var string */
    protected $sSearchModel = 'category';

    protected function extendItemCollection()
    {
        CategoryCollection::extend(function ($obCollection) {
            /** @var CategoryCollection $obCollection */
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
        return Category::class;
    }

    protected function getItemClass()
    {
        return CategoryItem::class;
    }
}
