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
            $table->unsignedBigInteger('whs_handicap_import_id');

            $table->unsignedBigInteger('whs_no')->comment('Identifies a player within the World Handicap System and serves as a foreign key reference to link tournament participants with their maintained player profiles in the system');
            $table->unsignedTinyInteger('whs_handicap_index')->comment('The WHS handicap index imported from the WHS system for the player');
            $table->unsignedTinyInteger('final_whs_handicap_index')->comment("Manual correction of WHS index imported from WHS. This will store the manual correction before it will be used in the handicap configuration and formula.");

            //name and sex
            $table->string('name')->comment('Name of the player');
            $table->enum('sex', ['M', 'F'])->comment('Sex of the player');

            $table->enum('handicap_type', ['reg', 'plus', 'none'])->default('reg')->comment('Indicates if the WHS handicap index is a regular or plus handicap');
            $table->unique(['tournament_id', 'whs_handicap_import_id', 'whs_no'], 'tournament_whs_handicap_unique');


            $table->boolean('is_adjusted')->default(false)->comment('Indicates if the WHS handicap index has been manually adjusted for the tournament');
            $table->boolean('active')->default(true);
            $table->text('remarks')->nullable()->default(null);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');


            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('restrict');
            $table->foreign('whs_handicap_import_id')->references('whs_handicap_import_id')->on('whs_handicap_imports')->onDelete('restrict');
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
