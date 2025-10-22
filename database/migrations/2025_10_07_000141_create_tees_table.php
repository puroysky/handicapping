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
        Schema::create('tees', function (Blueprint $table) {

            $table->id('tee_id');
            $table->string('tee_code', 10)->comment('Unique code for the tee ex: BLUE, WHITE, RED');
            $table->string('tee_name', 100)->comment('Name of the tee, e.g., Back, Middle, Front');
            $table->string('tee_desc', 255)->nullable()->default(null);
            $table->unsignedBigInteger('course_id');

            $table->text('remarks')->nullable()->default(null);
            $table->boolean('active')->default(true);

            $table->unique(['tee_code', 'course_id'], 'unique_tee_code_course')->comment('Ensure unique combination of tee code and course');

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
        Schema::dropIfExists('tees');
    }
};
