<?php namespace BizMark\ElasticSearchShopaholic;

use BizMark\ElasticSearchShopaholic\Console\Reindex;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Event;
use System\Classes\PluginBase;

use BizMark\ElasticSearchShopaholic\Classes\Event\ExtendFieldHandler;
use BizMark\ElasticSearchShopaholic\Classes\Event\CategoryModelHandler;
use BizMark\ElasticSearchShopaholic\Classes\Event\ProductModelHandler;
use BizMark\ElasticSearchShopaholic\Classes\Event\BrandModelHandler;
use BizMark\ElasticSearchShopaholic\Classes\Event\TagModelHandler;
use System\Classes\PluginManager;

/**
 * Class Plugin
 * @package BizMark\ElasticSearchShopaholic
 * @author Nick Khaetsky, nick@biz-mark.ru, Biz-Mark
 */
class Plugin extends PluginBase
{
    /** @var array Plugin dependencies */
    public $require = ['Lovata.Shopaholic', 'Lovata.Toolbox'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'bizmark.elasticsearchshopaholic::lang.plugins.name',
            'description' => 'bizmark.elasticsearchshopaholic::lang.plugins.description',
            'author'      => 'Biz-Mark, Nick Khaetsky',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Plugin boot method
     */
    public function boot()
    {
        $this->addEventListener();
    }

    /**
     * register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConsoleCommand('shopaholic:elastic.reindex', Reindex::class);

        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('bizmark.elasticsearchshopaholic::hosts'))
                ->build();
        });
    }

    /**
     * Add event listeners
     */
    protected function addEventListener()
    {
        Event::subscribe(ExtendFieldHandler::class);
        Event::subscribe(CategoryModelHandler::class);
        Event::subscribe(ProductModelHandler::class);
        Event::subscribe(BrandModelHandler::class);

        if (PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic')) {
            Event::subscribe(TagModelHandler::class);
        }
    }
}
