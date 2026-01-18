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
        Schema::table('attenance_departure', function (Blueprint $table) {
            $table->date('the_day_date')->comment("تاريخ اليوم الفعلى الى مفرود فيه سحب بصمه والنظام ممكن يضع له قيمه حتى لو لم يتم بصمه به");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attenance_departure', function (Blueprint $table) {
            //
        });
    }
};
