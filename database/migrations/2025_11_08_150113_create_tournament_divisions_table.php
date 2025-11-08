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
        Schema::create('tournament_divisions', function (Blueprint $table) {
            $table->id('tournament_division_id');
            $table->string('tournament_division_name', 100);
            $table->string('tournament_division_desc', 255)->nullable()->default(null);

            $table->enum('tournament_division_sex', ['M', 'F', 'X'])->default('X');
            $table->enum('tournament_division_participant_type', ['member', 'guest', 'mixed'])->default('mixed');
            $table->integer('age_min')->nullable()->default(null)->comment('Minimum age for the division');
            $table->integer('age_max')->nullable()->default(null)->comment('Maximum age for the division');
            $table->decimal('handicap_index_min', 5, 2)->nullable()->default(null)->comment('Minimum handicap index for the division');
            $table->decimal('handicap_index_max', 5, 2)->nullable()->default(null)->comment('Maximum handicap index for the division');
            $table->text('remarks')->nullable()->default(null)->comment('Internal remarks about the division');
            $table->boolean('active')->default(true);

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_divisions');
    }
};
