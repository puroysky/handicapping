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
        Schema::create('scorecards', function (Blueprint $table) {

            $table->id('scorecard_id');
            $table->string('scorecard_code', 10)->unique()->comment('Unique code for the scorecard ex: N, S, NRT, SRH');
            $table->string('scorecard_name', 100);
            $table->string('scorecard_desc', 255)->nullable()->default(null);

            $table->unsignedBigInteger('course_id');

            $table->unsignedTinyInteger('total_holes')->default(18)->comment('Total number of holes on the scorecard, typically 9 or 18');
            $table->unsignedTinyInteger('x_value')->default(0);

            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('score_cards');
    }
};
