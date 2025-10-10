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
        Schema::create('scorecard_pars', function (Blueprint $table) {

            $table->id('scorecard_par_id');

            $table->unsignedBigInteger('scorecard_id');
            $table->unsignedTinyInteger('hole')->unsigned();
            $table->unsignedTinyInteger('par')->unsigned();
            $table->unique(['scorecard_id', 'hole'], 'scorecard_par_key');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('scorecard_id')->references('scorecard_id')->on('scorecards')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pars');
    }
};
