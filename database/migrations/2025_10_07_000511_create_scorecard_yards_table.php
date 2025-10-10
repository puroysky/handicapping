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
        Schema::create('scorecard_yards', function (Blueprint $table) {
            $table->id('scorecard_yard_id');
            $table->unsignedBigInteger('scorecard_id');
            $table->unsignedBigInteger('scorecard_hole_id');
            $table->unsignedBigInteger('tee_id');
            $table->unsignedSmallInteger('yardage');



            // Composite unique key for scorecard, tee, and hole
            // Ensure unique combination of scorecard, tee, and hole
            $table->unique(['scorecard_hole_id', 'tee_id'], 'scorecard_yards_unique');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');

            $table->foreign('scorecard_id')->references('scorecard_id')->on('scorecards')->onDelete('restrict');
            $table->foreign('scorecard_hole_id')->references('scorecard_hole_id')->on('scorecard_holes')->onDelete('restrict');
            $table->foreign('tee_id')->references('tee_id')->on('tees')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scorecard_yards');
    }
};
