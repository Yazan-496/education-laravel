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
        Schema::create('stages_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('stage_id')->unsigned();
            $table->unsignedBiginteger('answer_id')->unsigned();

            $table->foreign('stage_id')->references('id')
                ->on('stages')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')
                ->on('answers')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages_answers');
    }
};
