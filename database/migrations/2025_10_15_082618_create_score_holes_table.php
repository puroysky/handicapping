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
        Schema::create('score_holes', function (Blueprint $table) {
            $table->id('score_hole_id');
            $table->unsignedBigInteger('score_id');
            $table->enum('score_type', ['hole by hole', 'gross score'])->default('hole by hole')->comment('Type of score: hole by hole or gross score');


            $table->decimal('gross_score', 5, 2)->nullable()->default(null);
            $table->decimal('adjusted_score', 5, 2);



            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');

            $table->foreign('score_id')->references('score_id')->on('scores')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_holes');
    }
};
