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
        Schema::create('whs_handicap_imports', function (Blueprint $table) {
            $table->id('whs_handicap_import_id');
            $table->unsignedBigInteger('tournament_id');
            $table->string('orig_filename', 255);
            $table->string('stored_filename', 255);
            $table->string('file_path', 500);

            $table->text('remarks')->nullable()->default(null);
            $table->boolean('active')->default(true);


            $table->unsignedBigInteger('created_by')->comment('User who uploaded/imported the file');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');

            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whs_handicap_imports');
    }
};
