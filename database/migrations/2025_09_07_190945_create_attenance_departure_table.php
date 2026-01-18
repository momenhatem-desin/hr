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
        Schema::create('attenance_departure', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_cln_periods_id')->comment("كود الشهر المالى ");
            $table->bigInteger('employees_code')->comment("كود الموظف الثابت");
            $table->decimal("shift_hour_contract",10,2)->nullable()->comment("عدد سعات العمل المتعاقد عليا"); 
            $table->tinyInteger('status_move')->nullable()->comment("1_checkin 2_checkout ");
            $table->date('dateIn')->nullable();
            $table->date('dateOut')->nullable();
            $table->time('timeIn')->nullable();
            $table->time('timeOut')->nullable();
            $table->string('variables',250)->nullable()->comment(" المتغيرات ");
            $table->decimal('attendance_dely',10,2)->default(0)->comment("قيمه حضور متاخر");
            $table->decimal('early_departure',10,2)->default(0)->comment("قيمه انصراف مبكر ");
            $table->string('azn_houres',250)->nullable()->comment("تفاصيل الاذن ان وجد ");
            $table->decimal("total_hours",10,2)->default(0)->comment("عدد سعات العمل بين توقيت الحضور والانصراف "); 
            $table->decimal("absen_hours",10,2)->default(0)->comment("عدد سعات الغياب بهذا اليوم"); 
            $table->decimal("additional_hours",10,2)->default(0)->comment("عدد سعات الاضافى بهذا اليوم"); 
            $table->dateTime('datetime_In')->nullable()->comment("  البصمة وقت الحضور ");
            $table->dateTime('datetime_out')->nullable()->comment("وقت الانصراف البصمة");
            $table->tinyInteger('is_made_action_on_emp')->default(0)->comment("هل تم اخذ اجراء على الموظف ");
            $table->integer('is_archived')->default(0)->comment("حالة الارشفة");
            $table->dateTime('archived_at')->nullable()->comment("تاريخ الارشفة");   
            $table->foreignId('archived_by')->nullable()->references('id')->on('admins')->onUpdate('cascade'); 
            $table->integer('vacations_types_id')->nullable()->comment("لو اجازة هيبقى كود الاجازة "); 
            $table->integer('occasions_id')->nullable()->comment(" لو اجازة رسميه نوع الاجازة الرسميه"); 
            $table->tinyInteger('cut')->default(0)->comment("0-noting,0.25-qurater dat .5-helf day 1-one day");
            $table->string("year_and_month",10)->comment(" السنة والشهر ")->nullable();
            $table->integer("Functiona_status")->comment("لحظه الراتب حالة الموظف واحد يعمل - صفر خارج الخدمة");
            $table->integer("branch_id")->nullable()->comment("الفرع التابع له الموظف لحظه هذا الارشيف");
            $table->tinyInteger('attendance_type')->default(1)->comment("1 = بصمة، 2 = يدوي");
            $table->foreignId('main_salary_employee_id')->nullable();
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
        Schema::dropIfExists('attenance_departure');
    }
};
