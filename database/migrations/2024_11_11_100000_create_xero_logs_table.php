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
        Schema::create('xero_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable();
            $table->string('type')->default('sync'); // sync, error, info
            $table->string('status')->default('success'); // success, failed, warning
            $table->text('message')->nullable();
            $table->text('error_details')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('sync_time')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
            $table->index(['organisation_id', 'type', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xero_logs');
    }
};

