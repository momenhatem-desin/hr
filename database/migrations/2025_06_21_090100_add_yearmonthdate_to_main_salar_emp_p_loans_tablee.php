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
        Schema::table('main_salary_employees_p_loans', function (Blueprint $table) {
              $table->date('year_and_month_start_date')->nullable()->comment("يبدا السداد اول قسط بتاريخ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_salary_employees_p_loans', function (Blueprint $table) {
            //
        });
    }
};
