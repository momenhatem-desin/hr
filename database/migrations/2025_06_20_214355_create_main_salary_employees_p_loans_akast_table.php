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
        Schema::create('main_salary_p_loans_akast', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_salary_p_loans_id')->nullable()->references('id')->on('main_salary_employees_p_loans')->onUpdate('cascade')->onDelete('cascade');   
            $table->foreignId('main_salary_employee_id')->nullable()->references('id')->on('main_salary_employee')->onUpdate('cascade');      
            $table->decimal("month_kast_value",10,2)->comment("قيمة القسط الشهرى ");
            $table->integer('state')->nullable()->comment(" حالة الدفع صقر فى الانتظار واحد تم الدفع اثنين تم الدفع كاش");
            $table->string("year_and_month",10)->comment("تاريخ الاستحقاق ")->nullable();
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
        Schema::dropIfExists('main_salary_p_loans_akast');
    }
};
