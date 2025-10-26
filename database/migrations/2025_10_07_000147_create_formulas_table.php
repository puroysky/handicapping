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
        Schema::create('formulas', function (Blueprint $table) {


            $table->id('formula_id');
            $table->unsignedBigInteger('formula_type_id')->comment('Reference to formula_types table');
            $table->unsignedBigInteger('course_id')->comment('Reference to courses table');

            $table->string('formula_name', 100)->comment('Name of the formula, e.g., Adjusted Gross Score, Handicap Index');
            $table->string('formula_code', 20)->comment('Version of the formula, e.g., v1.0, v2.1')->unique();
            $table->string('formula_desc', 255)->nullable()->default(null);

            $table->string('formula_expression', 255)->comment('The mathematical expression of the formula');
            $table->json('formula_variables')->nullable()->default(null)->comment('List of components and its values used in the formula');

            $table->text('remarks')->nullable()->default(null);
            $table->boolean('active')->default(true);


            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');

            $table->foreign('formula_type_id')->references('formula_type_id')->on('formula_types')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulas');
    }
};
