# ElasticSearch for Shopaholic

This plugin allows you to use ElasticSearch as search engine for Shopaholic.

### Benefits
- Easy to install, easy to use
- Opened to your new ideas and features as contributions.

### Contributing

Check out plugin [repository](https://github.com/Biz-mark/ElasticSearch-Shopaholic).

### Installation

Install plugin from marketplace:
```
php artisan plugin:install BizMark.ElasticSearchShopaholic
```

Add ElasticSearch hosts parameter to your .env

```
ELASTICSEARCH_HOSTS=127.0.0.1
```

### Indexing

Every time you save Product, Category, Brand or Tag model, new data will be pushed to ElasticSearch.

If you want to reindex your existing data, just call this artisan command:

```
php artisan shopaholic:elastic.reindex
```

### Using 

To search, you need to use `search()` method at desired collection. Example:

```
$obProductCollection = ProductCollection::make()->active()->search('term');
```

---
© 2022, Biz-Mark under Commercial License.

Developed by Nick Khaetsky at Biz-Mark.
