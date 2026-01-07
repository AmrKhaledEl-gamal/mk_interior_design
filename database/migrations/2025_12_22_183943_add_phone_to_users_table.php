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
            $table->string('phone1')->nullable()->after('email');
            $table->string('phone2')->nullable()->after('phone1');
            // dorp name column and add first_name and last_name columns if needed
            $table->dropColumn('name');
            $table->string('first_name')->after('email');
            $table->string('last_name')->after('first_name');
            $table->string('description')->after('last_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone1');
            $table->dropColumn('phone2');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('description');
            $table->string('name')->after('email');
        });
    }
};
