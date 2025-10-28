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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id('tournament_id');
            $table->string('tournament_name', 100);
            $table->string('tournament_desc', 255)->nullable()->default(null);

            $table->date('tournament_start');
            $table->date('tournament_end');


            $table->date('score_diff_start_date')->nullable()->default(null);
            $table->date('score_diff_end_date')->nullable()->default(null);
            $table->unsignedSmallInteger('recent_scores_count')->nullable()->default(null);
            $table->enum('score_selection_type', ['HIGHEST', 'LOWEST'])->default('LOWEST');
            $table->unsignedSmallInteger('scores_to_average')->comment('Number of scores to average for tournament handicap index calculation')->nullable()->default(null);


            $table->string('handicap_formula_expression', 255)->nullable()->default(null);


            $table->datetime('cancelled_at')->nullable()->default(null);
            $table->unsignedBigInteger('cancelled_by')->nullable()->default(null);
            $table->string('cancel_reason', 255)->nullable()->default(null);


            $table->text('remarks')->nullable()->default(null);
            $table->enum('active', ['active', 'completed', 'cancelled'])->default('active');

            $table->unsignedBigInteger('scorecard_id')->nullable()->default(null);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('scorecard_id')->references('scorecard_id')->on('scorecards')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
