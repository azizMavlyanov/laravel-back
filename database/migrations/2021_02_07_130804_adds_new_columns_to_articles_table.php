<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsNewColumnsToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('heading');
            $table->string('subheading');
            $table->string('slug')->unique()->nullable();
            $table->string('meta')->nullable();
            $table->integer('version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['heading']);
            $table->dropColumn(['subheading']);
            $table->dropColumn(['slug']);
            $table->dropColumn(['meta']);
            $table->dropColumn(['version']);
        });
    }
}
