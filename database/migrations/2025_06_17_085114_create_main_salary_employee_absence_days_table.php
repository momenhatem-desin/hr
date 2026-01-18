<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main_salary_employee_absence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_cln_periods_id')->references('id')->on('finance_cln_periods')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('main_salary_employee_id')->references('id')->on('main_salary_employee')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('is_auto')->default(0)->comment("هل تلقائى من النظام ام بشكل يدوى");
             $table->integer('kind_auto')->default(0)->comment("هل تلقائى من النظامن نوع الامر التلقائى");
            $table->bigInteger('employees_code');
            $table->decimal("emp_day_price",10,2)->comment("قيمة يوم المرتب")->default('0');
            $table->decimal("value",10,2)->comment("كم يوم غياب")->default('0');
            $table->decimal("total",10,2)->comment("أجمالى غياب")->default('0'); 
            $table->string('notes',100)->nullable();
            $table->integer('is_archived')->nullable()->comment("حالة الارشفة");
            $table->dateTime('archived_at')->nullable()->comment("تاريخ الارشفة");   
            $table->foreignId('archived_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');         
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('active')->default(1);
            $table->timestamps();
            $table->integer('com_code');
        });
      //  DB::statement("ALTER TABLE `foo` comment `comment`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_salary_employee_absence');
    }
};
