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
        Schema::create('scores', function (Blueprint $table) {

            $table->id('score_id');

            // Foreign keys
            $table->unsignedBigInteger('player_profile_id')->comment('Player profile ID who played the round');
            $table->unsignedBigInteger('user_profile_id')->comment('User profile ID who played the round');
            $table->unsignedBigInteger('user_id')->comment('User ID who played the round');




            $table->unsignedBigInteger('tournament_id')->comment('Tournament played');
            $table->unsignedBigInteger('tournament_course_id')->comment('Course played');
            $table->unsignedBigInteger('tee_id')->comment('Tee played');

            // Round info
            $table->date('score_date')->nullable()->comment('Date when the round was played or recorded');
            $table->enum('scoring_method', ['hole_by_hole', 'adjusted_score'])->default('hole_by_hole')->comment('Method of scoring');
            $table->enum('entry_type', ['manual', 'import'])->default('manual')->comment('How the score was entered');
            $table->enum('side', ['front', 'back', 'both'])->comment('Front 9, Back 9, or Both (full 18 holes)');

            // Score totals
            $table->unsignedSmallInteger('gross_score')->nullable()->comment('Total strokes taken');
            $table->unsignedSmallInteger('adjusted_score')->comment('Score after adjustments');
            $table->unsignedSmallInteger('net_score')->comment('Score after applying handicap');

            // Par totals
            // $table->unsignedTinyInteger('front_par_total')->nullable()->comment('Total par for holes 1–9');
            // $table->unsignedTinyInteger('back_par_total')->nullable()->comment('Total par for holes 10–18');
            // $table->unsignedTinyInteger('total_par')->nullable()->comment('Overall par for the round');

            // Handicap info
            // $table->decimal('handicap_index', 4, 1)->nullable()->comment('Official handicap index at time of round');
            // $table->unsignedSmallInteger('course_handicap')->nullable()->comment('Handicap adjusted for course slope/rating');
            // $table->decimal('score_differential', 5, 2)->nullable()->comment('Used for handicap index calculation');

            // Status and audit
            $table->boolean('is_verified')->default(true)->comment('Verified by tournament official');
            $table->unsignedBigInteger('verified_by')->nullable()->default(null);
            $table->timestamp('verified_at')->nullable()->default(null);
            $table->text('remarks')->nullable()->comment('Optional notes');

            // Audit trail
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            // Foreign keys
            $table->foreign('player_profile_id')->references('player_profile_id')->on('player_profiles')->onDelete('restrict');
            $table->foreign('user_profile_id')->references('user_profile_id')->on('user_profiles')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('restrict');
            $table->foreign('tournament_course_id')->references('tournament_course_id')->on('tournament_courses')->onDelete('restrict');
            $table->foreign('tee_id')->references('tee_id')->on('tees')->onDelete('restrict');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_cards');
    }
};
