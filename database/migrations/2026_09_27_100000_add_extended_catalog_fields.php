<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('fragrance_story')->nullable()->after('description');
            $table->string('availability_status')->default('in_stock')->after('is_available');
            $table->integer('low_stock_threshold')->default(5)->after('stock_quantity');
            $table->string('focus_keyword')->nullable()->after('seo_title');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            $table->string('sku')->nullable()->after('size');
            $table->boolean('is_available')->default(true)->after('stock_quantity');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->string('status')->default('approved')->after('comment');
            $table->boolean('is_featured')->default(false)->after('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['fragrance_story', 'availability_status', 'low_stock_threshold', 'focus_keyword']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['discount_price', 'sku', 'is_available']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_featured']);
        });
    }
};
