<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type')->nullable()->default('Eau de Parfum');
            $table->string('ad_headline')->nullable();
            $table->text('ad_copy')->nullable();
            $table->string('ad_cta')->nullable()->default('Shop Now');
            $table->text('instagram_caption')->nullable();
            $table->text('facebook_caption')->nullable();
            $table->text('tiktok_caption')->nullable();
            $table->text('whatsapp_caption')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('image_alt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'product_type',
                'ad_headline',
                'ad_copy',
                'ad_cta',
                'instagram_caption',
                'facebook_caption',
                'tiktok_caption',
                'whatsapp_caption',
                'seo_title',
                'meta_description',
                'image_alt',
            ]);
        });
    }
};
