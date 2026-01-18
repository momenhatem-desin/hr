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
        Schema::create('attenance_departure_actions_excel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_cln_periods_id');
            $table->bigInteger('employees_code')->comment("كود الموظف الثابت");
            $table->dateTime("datetimeAction"); 
            $table->tinyInteger('action_type')->nullable()->comment("نوع حركة البصمة");
            $table->foreignId('main_salary_employee_id')->nullable();
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('com_code');
            $table->dateTime('created_at')->comment("تاريخ الاصافه");       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attenance_departure_actions_excel');
    }
};
