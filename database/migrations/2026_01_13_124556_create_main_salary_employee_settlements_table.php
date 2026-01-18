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
        Schema::create('main_salary_employee_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_cln_periods_id')->references('id')->on('finance_cln_periods')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('employees_code');

            $table->decimal("work_days_for", 10, 2)->nullable()->default(0)->comment("عدد ايام مستحقه");
            $table->decimal("work_days_for_total", 10, 2)->nullable()->default(0)->comment("اجمالى ايام مستحقه");
            $table->decimal("extra_days_for", 10, 2)->nullable()->default(0)->comment("عدد ايام اضافى");
            $table->decimal("extra_days_for_total", 10, 2)->nullable()->default(0)->comment("اجمالى ايام اضافى");
            $table->decimal("absence_back_for", 10, 2)->nullable()->default(0)->comment("عدد ايام رد غياب");
            $table->decimal("absence_back_total_for", 10, 2)->nullable()->default(0)->comment("اجمالى ايام رد غياب");
            $table->decimal("sanctions_back_for", 10, 2)->nullable()->default(0)->comment("عدد ايام جزاء");
            $table->decimal("sanctions_back_total_for", 10, 2)->nullable()->default(0)->comment("اجمالى ايام جزاء");
            $table->decimal("salary_difference_for", 10, 2)->nullable()->default(0)->comment("فرق راتب");
            $table->decimal("award_for", 10, 2)->nullable()->default(0)->comment("مكافئه");
            $table->decimal("allowance_for", 10, 2)->nullable()->default(0)->comment("بدل ");
            $table->decimal("total_for", 10, 2)->nullable()->default(0)->comment("اجمالى الاستحقاقات ");
            $table->decimal("absence_on", 10, 2)->nullable()->default(0)->comment("عدد ايام الغياب");
            $table->decimal("absence_total_on", 10, 2)->nullable()->default(0)->comment("اجمالى قيمة عدد ايام الغياب ");
            $table->decimal("sanctions_on", 10, 2)->nullable()->default(0)->comment("عدد ايام الجزاءات");
            $table->decimal("sanctions_total_on", 10, 2)->nullable()->default(0)->comment("اجمالى قيمة عدد ايام الجزاءات ");
            $table->decimal("cash_discound_on", 10, 2)->nullable()->default(0)->comment("اجمالى خصم نقدى ");
            $table->decimal("allowance_on", 10, 2)->nullable()->default(0)->comment("اجمالى خصم زى او رد بدل ");
            $table->decimal("midical_insurance_on", 10, 2)->nullable()->default(0)->comment("اجمالى خصم تامين طبى  ");
            $table->decimal("social_insurance_on", 10, 2)->nullable()->default(0)->comment("اجمالى خصم تامين اجتماعى ");
            $table->decimal("monthly_loan_on", 10, 2)->nullable()->default(0)->comment("اجمالى خصم سلف شهريه ");
            $table->decimal("permaneten_monthly_loan_on", 10, 2)->nullable()->default(0)->comment("اجمالى خصم سلف مستديمه ");
            $table->decimal("total_on", 10, 2)->nullable()->default(0)->comment("اجمالى خصم سلف شهريه ");
            $table->decimal("final_total", 10, 2)->nullable()->default(0)->comment("اجمالى خصم سلف شهريه ");
            $table->decimal("emp_day_price", 10, 2)->comment("قيمة يوم المرتب")->default('0');
            $table->decimal("emp_sal", 10, 2)->nullable()->default(0)->comment("راتب الموظف");
            $table->string('notes', 500)->nullable();
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('is_archived')->default(0)->comment("حالة الارشفة");
            $table->dateTime('archived_at')->nullable()->comment("تاريخ الارشفة");
            $table->foreignId('archived_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('com_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_salary_employee_settlements');
    }
};
