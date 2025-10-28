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
        Schema::create('participant_course_handicaps', function (Blueprint $table) {
            $table->id('participant_course_handicap_id');
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('tee_id');


            $table->unique(['participant_id', 'course_id', 'tee_id'], 'participant_course_unique');
            $table->unsignedTinyInteger('course_handicap')->nullable()->default(null);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');

            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('restrict');
            $table->foreign('tee_id')->references('tee_id')->on('tees')->onDelete('restrict');
            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('restrict');
            $table->foreign('participant_id')->references('participant_id')->on('participants')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_player_course_handicaps');
    }
};
