<?php namespace BizMark\ElasticSearchShopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class UpdateTableBrand
 * @package BizMark\ElasticSearchShopaholic\Updates
 * @author Nick Khaetsky, nick@biz-mark.ru, Biz-Mark
 */
class UpdateTableBrand extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_brands';

    /**
     * Apply migration
     */
    public function up()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || Schema::hasColumn(self::TABLE_NAME, 'search_synonym')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->text('search_synonym')->nullable();
            $obTable->text('search_content')->nullable();
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || !Schema::hasColumn(self::TABLE_NAME, 'search_synonym')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) {
            $obTable->dropColumn(['search_synonym', 'search_content']);
        });
    }
}
