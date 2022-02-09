# ElasticSearch for Shopaholic

This plugin allows you to use ElasticSearch as search engine for Shopaholic.

### Installation

```
php artisan plugin:install BizMark.ElasticSearchShopaholic
```

### Indexing

Every timw you save Product, Caregory, Brand or Tag model, new data will be pushed to ElasticSearch.

Id you want to reindex your existing data, just call this arrisan command:

```
pho arartisan shopaholic:elastic.reindex
```

### Using 

To search, you need to use `search()` method at desired collection. Example:

```
$obProductCollection = ProductCollection::make()->active()->search('term');
```

---
© 2022, Biz-Mark under Commercial License.

Developed by Nick Khaetsky at Biz-Mark.
