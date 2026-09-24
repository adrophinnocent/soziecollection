<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('brand')->default('Sozie Collection');
            $table->enum('gender', ['women', 'men', 'unisex'])->default('unisex');
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->integer('stock_quantity')->default(50);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_limited_edition')->default(false);

            // Fragrance Details
            $table->text('description');
            $table->json('why_you_will_love_it')->nullable();
            $table->string('fragrance_family'); // e.g. Floral Fruity, Woody Amber, Oriental
            $table->string('scent_type'); // e.g. Fresh, Sweet, Floral, Woody, Spicy, Vanilla
            $table->string('top_notes');
            $table->string('heart_notes');
            $table->string('base_notes');
            $table->string('concentration')->default('Eau de Parfum');
            $table->string('default_size')->default('50ml');
            $table->string('longevity')->default('8 - 12 Hours');
            $table->string('occasion')->default('Everyday, Date Night');
            $table->string('season')->default('All Seasons');
            $table->string('time_of_day')->default('Day & Night');
            $table->string('intensity')->default('Medium / Strong');
            $table->string('sillage')->default('Moderate to High');

            // Media
            $table->json('images')->nullable();
            $table->string('campaign_image')->nullable();
            $table->string('video_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
