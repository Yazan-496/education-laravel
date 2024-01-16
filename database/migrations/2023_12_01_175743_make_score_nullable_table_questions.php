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
            Schema::table('questions', function (Blueprint $table) {
                     $table->string('score')->nullable()->change();
                     $table->string('top')->nullable()->change();
                     $table->string('left')->nullable()->change();
                     $table->string('img_src')->nullable()->change();
                     $table->string('img_name')->nullable()->change();
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
