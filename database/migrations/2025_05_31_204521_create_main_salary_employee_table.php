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
        Schema::create('main_salary_employee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Finance_cln_periods_id')->references('id')->on('Finance_cln_periods')->onUpdate('cascade')->comment("كود الشهر المالى");
            $table->integer('employees_code')->comment("كود الموظف");
            $table->string("emp_name", 300)->comment("اسم الموظف");
            $table->decimal("day_price",10,2)->comment("   قيمة يوم المرتب")->default('0');
            $table->integer("is_Sensitive_manger_data")->nullable()->default('0')->comment("  بيانات حساسه هل من موظفين الادارة");
            $table->integer("branch_id")->nullable()->comment("الفرع التابع له الموظف لحظه هذا الارشيف");
            $table->integer("Functiona_status")->comment("لحظه الراتب حالة الموظف واحد يعمل - صفر خارج الخدمة");
            $table->integer("emp_Departments_code")->nullable()->comment("لحظه الراتب حالة الموظف");
            $table->integer("emp_jobs_id")->nullable()->comment("لحظه الراتب  الوظيفة");
            $table->decimal("additions",10,2)->nullable()->default('0')->comment("أجمالى الاضافى للمرتب ");
            $table->decimal("motivation",10,2)->nullable()->default('0')->comment("أجمالى الحافز ممكن ان يكون ثابت او متغير للمرتب ");
            $table->decimal("addtional_days_counter",10,2)->comment("أجمالى ايام الاضافى   للمرتب ")->nullable()->default('0');
            $table->decimal("addtional_days",10,2)->comment("أجمالى قيمة ايام الاضافى   للمرتب ")->nullable()->default('0'); 
            $table->decimal("absence_days_counter",10,2)->comment("أجمالى عدد ايام الغياب   للمرتب ")->nullable()->default('0'); 
            $table->decimal("absence_days",10,2)->comment("أجمالى قيمة ايام الغياب   للمرتب ")->nullable()->default('0');  
            $table->decimal("monthly_loan",10,2)->comment("أجمالى قيمة المستقتع سلف شهرية   للمرتب ")->nullable()->default('0');
            $table->decimal("permanent_loan",10,2)->comment("أجمالى قيمة المستقتع سلف مستديمة   للمرتب ")->nullable()->default('0');
            $table->decimal("discount",10,2)->comment("أجمالى قيمة   الخصومات للراتب   للمرتب ")->nullable()->default('0');
            $table->decimal("phones",10,2)->comment("أجمالى قيمة   الخصومات الهاتف للمرتب   للمرتب ")->nullable()->default('0');
            $table->decimal("socialinsurancecutmonthely",10,2)->comment("أجمالى قيمة     خصم التامين الاجتماعى   للمرتب ")->nullable()->default('0');
            $table->decimal("medicalinsurancecutmonthely",10,2)->comment("أجمالى قيمة     خصم التامين الطبى   للمرتب ")->nullable()->default('0');
            $table->decimal("fixed_suits",10,2)->comment(" قيمة البدلات الثابتة")->nullable()->default('0');
            $table->decimal("changable_suits",10,2)->comment(" قيمة البدلات المتغيرة")->nullable()->default('0');
            $table->decimal("total_benefits",10,2)->comment("أجمالى المستحق لهذا الموظف   للمرتب ")->nullable()->default('0');
            $table->decimal("total_deductions",10,2)->comment("أجمالى المستقطع لهذا الموظف   للمرتب ")->nullable()->default('0');
            $table->decimal("sanctions_days_counter_type1",10,2)->comment("أجمالى  عدد ايام الجزاء الموظف   للمرتب ")->nullable()->default('0');
            $table->decimal("sanctions_days_total_type1",10,2)->comment("  قيمة ايام الجزاء الموظف   للمرتب ")->nullable()->default('0');
            $table->decimal("sanctions_days_counter_type2",10,2)->comment("أجمالى  عدد ايام الجزاء البصمة الموظف   للمرتب ")->nullable()->default('0');
            $table->decimal("sanctions_days_total_type2",10,2)->comment("أجمالى  قيمة ايام الجزاءالبصمة الموظف   للمرتب ")->nullable()->default('0');//يتم الغاء
            $table->decimal("sanctions_days_counter_type3",10,2)->comment("أجمالى    عدد ايام الغياب الموظف   للمرتب ")->nullable()->default('0');
            $table->decimal("sanctions_days_total_type2_type1",10,2)->comment("أجمالى  قيمة الغياب  للمرتب ")->nullable()->default('0');
            $table->decimal("emp_sal",10,2)->comment(" قيمة المرتب ")->nullable()->default('0');
            $table->decimal("last_salary_remain_blance",10,2)->comment(" قيمة الراتب المرحل من الشهر السابق المرتب ")->nullable()->default('0');
            $table->decimal("last_main_salary_record_id",10,2)->comment(" رقم الراتب الشهر السابق")->nullable()->default('0');
            $table->decimal("final_the_net",10,2)->comment(" صافى قيمة المرتب")->nullable()->default('0');
            $table->string("year_and_month",10)->comment(" السنة والشهر ")->nullable();
            $table->integer("FINANCE_YR")->comment(" السنة المالية ")->nullable()->default('0');
            $table->integer("sal_cach_or_visa")->comment(" كاش ولا فيزا")->nullable()->default('0');
            $table->integer("is_stoped")->comment("هل هذا المرتب موقوف ولا لا")->nullable()->default('0');
            $table->integer("is_archived")->comment(" هل تم ارشفتو ")->nullable()->default('0');
            $table->dateTime("archived_date")->comment("تاريخ ارشفة هذا الراتب")->nullable();
            $table->foreignId("archived_by")->references("id")->on("admins")->onUpdate("cascade")->comment("من قام بالارشفة");
            $table->foreignId("added_by")->references("id")->on("admins")->onUpdate("cascade");
            $table->foreignId("updated_by")->nullable()->references("id")->on("admins")->onUpdate("cascade");
            $table->integer("com_code")->comment("لحظه الراتب  كود الشركة");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_salary_employee');
    }
};
