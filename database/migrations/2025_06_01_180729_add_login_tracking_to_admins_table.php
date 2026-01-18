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
        Schema::table('admins', function (Blueprint $table) {
        $table->boolean('is_logged_in')->default(false);
        $table->string('session_id')->nullable();
        $table->timestamp('last_activity')->nullable();
        $table->string('last_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
      $table->dropColumn(['is_logged_in', 'session_id', 'last_activity']);
       $table->dropColumn('last_ip');
        });
    }
};
