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
        Schema::table('finance_cln_periods', function (Blueprint $table) {
             $table->integer('is_open')->default(0)->comment("صفر بانتظار الفتح 1 مفتوح 2 مأرشف")->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_cln_periods', function (Blueprint $table) {
            //
        });
    }
};
