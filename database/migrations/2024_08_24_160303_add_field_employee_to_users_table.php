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
            // relation to positions table
            $table->foreignId('position_id')->after('id')->nullable()->constrained('positions')->onUpdate('cascade')->onDelete('set null');
            // field username
            $table->string('username')->after('name')->nullable();
            // field employee number
            $table->string('phone')->after('username')->nullable();
            // field join year
            $table->string('join_year')->after('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['position_id', 'username', 'phone', 'join_year']);
        });
    }
};
