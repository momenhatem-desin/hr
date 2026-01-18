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
        Schema::create('main_employee_investigation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_cln_periods_id')->references('id')->on('finance_cln_periods')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('is_auto')->comment("هل تلقائى من النظام ام بشكل يدوى");
            $table->bigInteger('employees_code');
            $table->text("content")->comment("المحتوى "); 
            $table->string('notes',300)->nullable();
            $table->integer('is_archived')->nullable()->comment("حالة الارشفة تعتبر هى الاعتماد");
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
        Schema::dropIfExists('main_employee_sanction_investigation');
    }
};
