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
        Schema::create('organisations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('organisation_id');
            $table->string('api_key')->nullable();
            $table->string('name');
            $table->string('legal_name');
            $table->string('pays_tax')->nullable();
            $table->string('version')->nullable();
            $table->string('organisation_type')->nullable();
            $table->string('base_currency')->nullable();
            $table->string('country_code')->nullable();
            $table->boolean('is_demo_company')->default(false);
            $table->string('organisation_status')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('employer_identification_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('financial_year_end_day')->nullable();
            $table->string('financial_year_end_month')->nullable();
            $table->string('sales_tax_basis')->nullable();
            $table->string('sales_tax_period')->nullable();
            $table->string('default_sales_tax')->nullable();
            $table->string('default_purchases_tax')->nullable();
            $table->string('period_lock_date')->nullable();
            $table->string('end_of_year_lock_date')->nullable();
            $table->string('created_date_utc')->nullable();
            $table->string('timezone')->nullable();
            $table->string('organisation_entity_type')->nullable();
            $table->string('short_code')->nullable();
            $table->string('class')->nullable();
            $table->string('edition')->nullable();
            $table->string('line_of_business')->nullable();
            $table->json('external_links')->nullable();
            $table->json('payment_terms')->nullable();
            $table->timestamp('last_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};

