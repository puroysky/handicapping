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
        Schema::create('participant_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('course_id');


            $table->decimal('course_handicap', 4, 2)->nullable()->default(null);
            $table->decimal('final_course_handicap', 4, 2)->nullable()->default(null);


            // Composite primary key
            $table->primary(['participant_id', 'course_id'], 'participant_courses_primary');

            $table->unsignedBigInteger('tournament_id');
            $table->decimal('course_handicap', 4, 2)->nullable()->default(null);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');

            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('restrict');
            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('restrict');
            $table->foreign('participant_id')->references('participant_id')->on('participants')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_courses');
    }
};
