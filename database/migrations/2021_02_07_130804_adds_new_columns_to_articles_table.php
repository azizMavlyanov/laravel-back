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
            $table->string('heading', 300);
            $table->string('subheading', 300);
            $table->string('slug', 300)->unique()->nullable();
            $table->string('meta', 300)->nullable();
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
