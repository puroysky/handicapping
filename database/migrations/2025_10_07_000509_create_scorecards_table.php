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
            $table->string('scorecard_code', 20)->comment('Unique code for the scorecard ex: N, S, NRT, SRH');
            $table->string('scorecard_name', 100);
            $table->string('scorecard_desc', 255)->nullable()->default(null);
            $table->enum('scorecard_type', ['tournament', 'regular'])->default('regular');



            $table->unique(['scorecard_code', 'course_id'], 'scorecard_code_course_key')->comment('Ensure unique scorecard code per course');
            $table->unsignedBigInteger('adjusted_gross_score_formula_id')->nullable()->default(null)->comment('Formula used for Adjusted Gross Score calculation');
            $table->unsignedBigInteger('score_differential_formula_id')->nullable()->default(null)->comment('Formula used for Score Differential calculation');
            $table->unsignedBigInteger('course_handicap_formula_id')->nullable()->default(null)->comment('Formula used for Handicap Index calculation');





            $table->unsignedBigInteger('course_id');


            $table->enum('x_value', ['BOGEY', 'DOUBLE_BOGEY', 'TRIPLE_BOGEY'])->default('DOUBLE_BOGEY')->comment('X value for the scorecard');

            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');

            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('restrict');




            $table->foreign('adjusted_gross_score_formula_id')->references('formula_id')->on('formulas')->onDelete('restrict');
            $table->foreign('score_differential_formula_id')->references('formula_id')->on('formulas')->onDelete('restrict');
            $table->foreign('course_handicap_formula_id')->references('formula_id')->on('formulas')->onDelete('restrict');
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
