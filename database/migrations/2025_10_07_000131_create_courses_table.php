<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Logging\OpenTestReporting\Status;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('course_code', 10)->unique()->comment('Unique code for the course ex: N, S, NRT, SRH');
            $table->string('course_name', 100);
            $table->string('course_desc', 255)->nullable()->default(null);
            $table->enum('course_type', ['public', 'private', 'semi_private', 'resort'])->default('public');
            $table->integer('total_holes')->default(18);
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
        Schema::dropIfExists('courses');
    }
};
