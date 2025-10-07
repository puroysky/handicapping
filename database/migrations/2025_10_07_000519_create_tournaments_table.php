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

            $table->dateTime('tournament_start');
            $table->dateTime('tournament_end');

            $table->unsignedBigInteger('course_id');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('restrict');
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
