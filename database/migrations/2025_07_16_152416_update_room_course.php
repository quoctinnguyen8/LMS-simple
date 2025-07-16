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
        Schema::table('rooms', function (Blueprint $table) {
            $table->String('seo_title', 500)
                ->nullable();
            $table->string('seo_image', 1000)
                ->nullable();
            $table->string('seo_description', 2000)
                ->nullable()->change();
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->String('seo_title', 500)
                ->nullable();
            $table->string('seo_image', 1000)
                ->nullable();
            $table->string('seo_description', 2000)
                ->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
