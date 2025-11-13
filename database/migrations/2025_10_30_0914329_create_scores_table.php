<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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


            $table->unsignedBigInteger('participant_id')->nullable()->comment('Participant ID who played the round');
            $table->unsignedBigInteger('tournament_id')->comment('Tournament played');
            $table->unsignedBigInteger('tournament_course_id')->comment('Used for tournament rounds only');

            $table->unsignedBigInteger('division_id')->comment('Division played');
            $table->unsignedBigInteger('course_id')->comment('Identifies the golf course where the round was played, applicable to both tournament and non-tournament rounds');
            $table->unsignedBigInteger('tee_id')->comment('Tee played');

            // Round info
            $table->date('date_played')->nullable()->comment('Date when the round was played or recorded');
            $table->enum('scoring_method', ['hbh', 'adj'])->default('hbh')->comment('Method of scoring used: Handicap By Hole or Adjusted Gross');

            $table->enum('score_type', ['tmt', 'reg'])->comment('Type of score');
            $table->enum('score_source', ['form', 'import', 'legacy'])->default('form')->comment('How the score was entered');
            $table->enum('holes_played', ['F9', 'B9', '18'])->comment('Front 9, Back 9, or 18 holes played');




            $table->decimal('handicap_index', 4, 1)->nullable()->default(NULL)->comment('Actual handicap index of the player at time of round');
            $table->enum('handicap_category', ['reg', 'plus', 'none', 'legacy'])->default('reg')->comment('Type of handicap index used: WHS official, local club, none, or unknown');
            $table->enum('handicap_index_source', ['tournament', 'whs', 'local', 'legacy'])->nullable()->comment('Type of handicap index used: tournament, WHS official, local club, or unknown');
            $table->unsignedSmallInteger('course_handicap')->nullable()->comment('Course handicap for the player at time of round');

            $table->unsignedSmallInteger('gross_score')->nullable()->comment('Total strokes taken');
            $table->unsignedSmallInteger('adjusted_gross_score')->comment('Score after adjustments');
            $table->unsignedSmallInteger('net_score')->nullable()->default(NULL)->comment('Score after applying handicap');
            $table->decimal('score_differential', 5, 2)->comment('Calculated score differential for the round');



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

            $table->foreign('participant_id')->references('participant_id')->on('participants')->onDelete('restrict');
            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('restrict');
            $table->foreign('tournament_course_id')->references('tournament_course_id')->on('tournament_courses')->onDelete('restrict');
            $table->foreign('division_id')->references('division_id')->on('divisions')->onDelete('restrict');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('restrict');
            $table->foreign('tee_id')->references('tee_id')->on('tees')->onDelete('restrict');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });

        // Add check constraints using raw SQL
        DB::statement('ALTER TABLE scores ADD CONSTRAINT chk_handicap_index_required CHECK (NOT (score_source IN (\'import\', \'form\') AND handicap_index IS NULL))');
        DB::statement('ALTER TABLE scores ADD CONSTRAINT chk_net_score_required CHECK (NOT (score_source IN (\'import\', \'form\') AND net_score IS NULL))');
        DB::statement('ALTER TABLE scores ADD CONSTRAINT chk_participant_id_required CHECK (NOT (score_source IN (\'import\', \'form\') AND participant_id IS NULL))');
        DB::statement('ALTER TABLE scores ADD CONSTRAINT chk_course_handicap_required CHECK (NOT (score_source IN (\'import\', \'form\') AND course_handicap IS NULL))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_cards');
    }
};
