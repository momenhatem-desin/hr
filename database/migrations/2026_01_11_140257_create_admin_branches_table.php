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
        Schema::create('admin_branches', function (Blueprint $table) {
         $table->id();
         $table->integer('admin_id');
         $table->integer('branch_id');
         $table->tinyInteger('is_default')->default(0);
         $table->integer('com_code');
         $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_branches');
    }
};
