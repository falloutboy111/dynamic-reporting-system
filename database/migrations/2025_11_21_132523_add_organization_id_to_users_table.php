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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('organisation_id')->nullable()->after('email')->constrained('organisations')->onDelete('cascade');
            $table->index('organisation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organisation_id']);
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        });
    }
};
