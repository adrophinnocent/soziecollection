<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('eyebrow')->nullable()->after('title');
            $table->string('headline')->nullable()->after('eyebrow');
            $table->string('highlight_text')->nullable()->after('headline');
            $table->string('mobile_image')->nullable()->after('image');
            $table->string('secondary_button_text', 60)->nullable()->after('button_text');
            $table->string('secondary_button_link')->nullable()->after('button_link');
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'sort_order']);
            $table->dropColumn([
                'eyebrow',
                'headline',
                'highlight_text',
                'mobile_image',
                'secondary_button_text',
                'secondary_button_link',
            ]);
        });
    }
};
