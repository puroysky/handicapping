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
        Schema::create('formula_types', function (Blueprint $table) {
            $table->id('formula_type_id');
            $table->string('formula_type_code', 10)->comment('Unique code for the formula ex: AGS, SD, HI')->unique();
            $table->string('formula_type_name', 100)->comment('Name of the formula, e.g., Back, Middle, Front');
            $table->string('formula_type_desc', 255)->nullable()->default(null);
            $table->json('formula_type_fields')->nullable()->comment('List of fields used in the formula');
            $table->text('remarks')->nullable()->default(null);
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
        Schema::dropIfExists('formula_types');
    }
};
