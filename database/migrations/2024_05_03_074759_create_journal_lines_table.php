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
        Schema::create('journal_lines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('journal_id');
            $table->string('journal_line_id');
            $table->string('account_id');
            $table->string('account_code');
            $table->string('account_type');
            $table->string('account_name');
            $table->text('description')->nullable();
            $table->string('net_amount');
            $table->string('gross_amount');
            $table->string('tax_amount');
            $table->string('tax_type')->nullable();
            $table->string('tax_name')->nullable();
            $table->json('tracking_categories')->nullable();
            $table->timestamps();

            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_lines');
    }
};

