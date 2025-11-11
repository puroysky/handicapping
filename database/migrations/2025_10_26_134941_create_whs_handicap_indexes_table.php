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
        Schema::create('whs_handicap_indexes', function (Blueprint $table) {
            $table->id('whs_handicap_index_id');
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('whs_no')->comment('Identifies a player within the World Handicap System and serves as a foreign key reference to link tournament participants with their maintained player profiles in the system');
            $table->unsignedTinyInteger('whs_handicap_index')->comment('The WHS handicap index imported from the WHS system for the player');
            $table->unsignedTinyInteger('final_whs_handicap_index')->comment("Manual correction of WHS index imported from WHS. This will store the manual correction before it will be used in the handicap configuration and formula.");
            $table->unique(['tournament_id', 'whs_no'], 'tournament_whs_no_unique');


            $table->boolean('is_adjusted')->default(false)->comment('Indicates if the WHS handicap index has been manually adjusted for the tournament');
            $table->text('remarks')->nullable()->default(null);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');


            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('whs_no')->references('whs_no')->on('player_profiles')->onDelete('restrict');
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
