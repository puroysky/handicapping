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
        Schema::create('participants', function (Blueprint $table) {
            $table->id('participant_id');
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('player_profile_id');

            $table->unsignedTinyInteger('whs_handicap_index')->nullable()->default(null);
            $table->unsignedTinyInteger('local_handicap_index')->nullable()->default(null);
            $table->unsignedTinyInteger('tournament_handicap_index')->nullable()->default(null);
            $table->enum('index_type', ['WHS', 'LOCAL', 'TOURNAMENT'])->nullable()->default(null)->comment('Indicates whether the handicap index is based on WHS or Local system');
            $table->text('remarks')->nullable()->default(null);



            $table->unique(['tournament_id', 'user_id'], 'tournament_user_unique');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');



            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('player_profile_id')->references('player_profile_id')->on('player_profiles')->onDelete('restrict');
            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_players');
    }
};
