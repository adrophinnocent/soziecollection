<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('show_in_hero')->default(true)->after('is_active');
            $table->boolean('show_in_gallery')->default(true)->after('show_in_hero');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['show_in_hero', 'show_in_gallery']);
        });
    }
};
