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
        Schema::create('main_salary_employees_p_loans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employees_code');
            $table->decimal("emp_sal",10,2)->comment("مرتب الموظف")->default('0');
            $table->decimal("total",10,2)->comment("أجمالى السلفه ")->default('0'); 
            $table->integer('month_number')->comment("عدد الشهور للاقساط ");
            $table->decimal("month_kast_value",10,2)->comment("قيمة القسط الشهرى ");
            $table->string("year_and_month_start",10)->comment("يبدا السداد من الشهر المالى ")->nullable();
            $table->decimal("what_pait",10,2)->comment("أجمالى المدفوع ")->default('0'); 
            $table->decimal("what_remain",10,2)->comment("أجمالى المتبقى ")->default('0'); 
            $table->integer('is_dismissail_done')->comment("حاله الصرف ")->default('0');
            $table->dateTime('dismissail_at')->nullable();
            $table->foreignId('dismissail_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->string('notes',100)->nullable();
            $table->integer('is_archived')->nullable()->comment("حالة الارشفة");
            $table->dateTime('archived_at')->nullable()->comment("تاريخ الارشفة");   
            $table->foreignId('archived_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');         
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->timestamps();
            $table->integer('com_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_salary_employees_p_loans');
    }
};
