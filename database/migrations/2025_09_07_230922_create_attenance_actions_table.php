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
        Schema::create('attenance_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_departure_id')->references('id')->on('attenance_departure')->onUpdate('cascade');
            $table->foreignId('finance_cln_periods_id')->comment("كود الشهر المالى ");
            $table->bigInteger('employees_code')->comment("كود الموظف الثابت");
            $table->bigInteger('attenance_departure_actions_excel_id')->comment("رقم لبصمه فى الارشيف ");
            $table->dateTime("datetimeAction")->comment("توقيت البصمة ");
            $table->tinyInteger('action_type')->comment("نوع حركة البصمة");
             $table->integer('is_archived')->default(0)->comment("حالة الارشفة");
            $table->tinyInteger('it_is_active_with_parent')->default(0)->comment("هل تم اخذ اجراء  بتقفيل الاب ");
            $table->tinyInteger('added_method')->default(1)->comment("dynamic pasma-1 manual-2");
            $table->tinyInteger('is_made_action_on_emp')->default(0)->comment("هل تم اخذ اجراء على الموظف ");
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('com_code');
            $table->timestamps();   
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attenance_actions');
    }
};
