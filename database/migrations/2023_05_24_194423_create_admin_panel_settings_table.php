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
        Schema::create('admin_panel_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name',250);
            $table->tinyInteger('saysem_status')->default('1')->comment('واحد مفعل - صفر معطل');
            $table->string('image',250)->nullable();
            $table->string('phones',250); 
            $table->string('address',250);
            $table->string('email',100);
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->integer('com_code');
            $table->tinyInteger('type_vacation')->default('0')->comment('واحد اخرى - صفر سنوى');
            $table->decimal('quinty_vacstion',10,2)->default(0)->comment("عدد ايام الحضور");
            $table->decimal('after_miniute_calculate_delay',10,2)->default(0)->comment("بعد كم دقيقة نحسب تاخير حضور	");
            $table->decimal('after_miniute_calculate_early_departure',10,2)->default(0)->comment("بعد كم دقيقة نحسب انصراف مبكر	");
            $table->decimal('after_miniute_quarterday',10,2)->default(0)->comment("بعد كم مره الانصارف المبكر او الحضور المتأخر نحصم ربع يوم	");
            $table->decimal('after_time_half_daycut',10,2)->default(0)->comment("بعد كم مرة تأخير او انصارف مبكر نخصم نص يوم	");
            $table->decimal('after_time_allday_daycut',10,2)->default(0)->comment("نخصم بعد كم مره تاخير او انصارف مبكر يوم كامل	");
            $table->decimal('number_addinal_get',10,2)->default(0)->comment("فى كام تحسب سعر الساعه");
            $table->decimal('monthly_vacation_balance',10,2)->default(0)->comment("رصيد اجازات الموظف الشهري	");
            $table->decimal('after_days_begin_vacation',10,2)->default(0)->comment("بعد كم يوم ينزل للموظف رصيد اجازات	");
            $table->decimal('first_balance_begin_vacation',10,2)->default(0)->comment("الرصيد الاولي المرحل عند تفعيل الاجازات للموظف مثل نزول عشرة ايام ونص بعد سته شهور للموظف	");
            $table->tinyInteger('is_transfer_vaccation')->default('0')->comment('واحد مفعل - صفرمعطل');
            $table->tinyInteger('is_pull_anuall_day_from_passma')->default('1')->comment('واحد تالقائى - صفر منول');
            $table->tinyInteger('is_outo_offect_passmaV')->default('1')->comment('واحد تالقائى - صفر لاتؤثر _2 يختار اذا حب');
            $table->decimal('sanctions_value_first_abcence',10,2)->default(0)->comment("قيمة خصم الايام بعد اول مرة غياب بدون اذن	");
            $table->decimal('sanctions_value_second_abcence',10,2)->default(0)->comment("قيمة خصم الايام بعد ثاني مرة غياب بدون اذن	");
            $table->decimal('sanctions_value_thaird_abcence',10,2)->default(0)->comment("قيمة خصم الايام بعد ثالث مرة غياب بدون اذن	");
            $table->decimal('sanctions_value_forth_abcence',10,2)->default(0)->comment("قيمة خصم الايام بعد رابع مرة غياب بدون اذن	");
            $table->integer('less_than_miniute_neglecting_passmaa')->default(3)->comment("اقل من كام دقيقه يتم اهمال البصمه الثانيه التاكديه للموظف خلال نفس الشفت");
            $table->decimal('max_hours_take_pasma_as_addtional',10,2)->default(4)->comment("اقل من كام ساع اضافيه عند انصراف الموظف نحتسب هذه البصمه كاسعات اضافيه للموظف وتاخذ هذ البصمه كانصراف والا ستكون بصمه شفت جديد  ");
            $table->tinyInteger('is_active_alerts_system_monitoring')->default('1')->comment('واحد مفعل - صفر معطل');


            $table->timestamps();
        });
        DB::table('admin_panel_settings')->insert(
            [
             ['company_name'=>'الشركة الاولي',
             'phones'=>'11222',
             'address'=>' العنوان',
             'email'=>"info@test.com",
             'added_by'=>1,
             'updated_by'=>1,
             'com_code'=>1,

            ],
          
        
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_panel_settings');
    }
};
