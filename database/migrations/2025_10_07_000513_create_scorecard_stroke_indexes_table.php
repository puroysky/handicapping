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
        Schema::create('scorecard_stroke_indexes', function (Blueprint $table) {
            $table->id('scorecard_stroke_index_id');
            $table->unsignedBigInteger('scorecard_id');
            $table->unsignedBigInteger('scorecard_hole_id');
            $table->unsignedTinyInteger('hole');
            $table->enum('sex', ['M', 'F'])->comment('M = Male, F = Female');
            $table->unsignedTinyInteger('stroke_index')->nullable()->default(null)->comment('Handicap for the hole, typically 1-18, null if not assigned');


            $table->unique(['scorecard_hole_id', 'sex'], 'scorecard_hole_handicap_key')->comment('Ensure unique combination of scorecard hole and gender');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');

            $table->foreign('scorecard_id')->references('scorecard_id')->on('scorecards')->onDelete('restrict');
            $table->foreign('scorecard_hole_id')->references('scorecard_hole_id')->on('scorecard_holes')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scorecard_hole_handicaps');
    }
};
