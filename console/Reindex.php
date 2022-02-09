<?php namespace BizMark\ElasticSearchShopaholic\Console;

use Elasticsearch\Client;
use Illuminate\Console\Command;
use Lovata\Shopaholic\Models\Brand;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use System\Classes\PluginManager;

/**
 * Reindex Command
 */
class Reindex extends Command
{
    /**
     * @var string name is the console command name
     */
    protected $name = 'shopaholic:elastic.reindex';

    /**
     * @var string description is the console command description
     */
    protected $description = 'Reindex products, categories, brands, tags';

    /**
     * @var Client ElasticSearch client
     */
    protected $obElasticSearch;

    /**
     * handle executes the console command
     */
    public function handle()
    {
        $this->obElasticSearch = app()->make(Client::class);

        $this->info('Starting reindexing products');
        $this->reindexProducts();
        $this->info('Starting reindexing categories');
        $this->reindexCategories();
        $this->info('Starting reindexing brands');
        $this->reindexBrands();
        if (PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic')) {
            $this->info('Starting reindexing tags');
            $this->reindexTags();
        }
        $this->info('Reindex successful');
    }

    protected function reindexProducts()
    {
        foreach(Product::query()->cursor() as $obElement) {
            $sIdKey = config('bizmark.elasticsearchshopaholic::product.key');
            $this->obElasticSearch->index([
                'index' => config('bizmark.elasticsearchshopaholic::product.index'),
                'id' => $obElement->$sIdKey,
                'body' => $obElement->getSearchBody()
            ]);
        }
    }

    protected function reindexCategories()
    {
        foreach(Category::query()->cursor() as $obElement) {
            $sIdKey = config('bizmark.elasticsearchshopaholic::category.key');
            $this->obElasticSearch->index([
                'index' => config('bizmark.elasticsearchshopaholic::category.index'),
                'id' => $obElement->$sIdKey,
                'body' => $obElement->getSearchBody()
            ]);
        }
    }

    protected function reindexBrands()
    {
        foreach(Brand::query()->cursor() as $obElement) {
            $sIdKey = config('bizmark.elasticsearchshopaholic::brand.key');
            $this->obElasticSearch->index([
                'index' => config('bizmark.elasticsearchshopaholic::brand.index'),
                'id' => $obElement->$sIdKey,
                'body' => $obElement->getSearchBody()
            ]);
        }
    }

    protected function reindexTags()
    {
        foreach(\Lovata\TagsShopaholic\Models\Tag::query()->cursor() as $obElement) {
            $sIdKey = config('bizmark.elasticsearchshopaholic::tag.key');
            $this->obElasticSearch->index([
                'index' => config('bizmark.elasticsearchshopaholic::tag.index'),
                'id' => $obElement->$sIdKey,
                'body' => $obElement->getSearchBody()
            ]);
        }
    }

    /**
     * getArguments get the console command arguments
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * getOptions get the console command options
     */
    protected function getOptions()
    {
        return [];
    }
}
