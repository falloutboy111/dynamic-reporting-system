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
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')->constrained('organisations')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('sql_query');
            $table->enum('visualization_type', ['chart', 'table'])->default('table');
            $table->json('chart_config')->nullable();
            $table->foreignUuid('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('organisation_id');
            $table->index('created_by');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
