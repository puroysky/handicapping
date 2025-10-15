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
        Schema::create('score_holes', function (Blueprint $table) {
            $table->id('score_hole_id');

            // Relationships
            $table->unsignedBigInteger('score_id')->comment('Linked to main score record');

            //  Hole details
            $table->unsignedTinyInteger('hole')->comment('Hole number (1–18)');
            $table->enum('side', ['front', 'back', 'both'])->comment('Front 9, Back 9, or Both'); // auto-set based on hole

            // Score data
            $table->string('raw_input', 2)
                ->nullable()
                ->comment('Actual input: numeric strokes or "X" for no score');

            $table->unsignedTinyInteger('strokes')
                ->nullable()
                ->comment('Number of strokes recorded (numeric only, parsed from raw_input)');


            // Audit
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            // Foreign keys
            $table->foreign('score_id')->references('score_id')->on('scores')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');

            // Ensure one record per hole per score
            $table->unique(['score_id', 'hole']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_holes');
    }
};
