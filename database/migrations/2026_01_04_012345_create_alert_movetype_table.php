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
        Schema::create('alert_movetype', function (Blueprint $table) {
           $table->id();
            $table->string('name',225);
            $table->string('name_en',225)->nullable();
            $table->unsignedBigInteger('alert_modules_id');
            $table->foreign('alert_modules_id')->references('id')->on('alert_modules')->onDelete('cascade')->onUpdate('cascade');
            $table->tinyInteger('active')->default(1);
         
        });

         DB::table('alert_movetype')->insert(
       [
        ['name'=>'تعديل الضبط العام','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه سنه ماليه','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل سنه ماليه','alert_modules_id'=>1,'active'=>1],
        ['name'=>'فتح سنه ماليه','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اغلاق سنه ماليه','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه فرع جديد','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل فرع ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'حذف  فرع ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه شفت جديد','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل شفت ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'حذف  شفت','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه اداره جديده','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل اداره','alert_modules_id'=>1,'active'=>1],
        ['name'=>'حذف اداره','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه وظيفه جديد','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل وظيفه ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'حذف  وظيفه ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه مؤهل جديد','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل موهل ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'حذف  مؤهل','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه مناسبه جديده','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل مناسبه ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'حذف مناسبه ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه نوع ترك عمل','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل نوع ترك عمل ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'حذف نوع ترك عمل ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه نوع جنسيه جديد','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل نوع الجنسيه ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'حذف الجنسيه ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه نوع ديانه جديده','alert_modules_id'=>1,'active'=>1],
        ['name'=>'تعديل نوع ديانه ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'حذف نوع ديانه ','alert_modules_id'=>1,'active'=>1],
        ['name'=>'اضافه موظف جديد','alert_modules_id'=>2,'active'=>1],
        ['name'=>'تعديل موظف  ','alert_modules_id'=>2,'active'=>1],
        ['name'=>'حذف  موظف ','alert_modules_id'=>2,'active'=>1],
        ['name'=>'ارفاق ملفات لموظف','alert_modules_id'=>2,'active'=>1],
        ['name'=>'حذف ملفات لموظف','alert_modules_id'=>2,'active'=>1],
        ['name'=>'تحديث راتب موظف','alert_modules_id'=>2,'active'=>1],
        ['name'=>'تحديث حافز موظف','alert_modules_id'=>2,'active'=>1],
        ['name'=>'اضافه بدل لموظف','alert_modules_id'=>2,'active'=>1],
        ['name'=>'تعديل بدل لموظف','alert_modules_id'=>2,'active'=>1],
        ['name'=>'حذف بدل لموظف','alert_modules_id'=>2,'active'=>1],
        ['name'=>'اضافه نوع مكافئه','alert_modules_id'=>2,'active'=>1],
        ['name'=>'تعديل نوع مكافئه','alert_modules_id'=>2,'active'=>1],
        ['name'=>'حذف نوع مكافئه','alert_modules_id'=>2,'active'=>1],
        ['name'=>'اضافه نوع خصومات','alert_modules_id'=>2,'active'=>1],
        ['name'=>'تعديل نوع خصومات','alert_modules_id'=>2,'active'=>1],
        ['name'=>'حذف نوع خصومات','alert_modules_id'=>2,'active'=>1],
        ['name'=>'اضافه نوع بدلات','alert_modules_id'=>2,'active'=>1],
        ['name'=>'تعديل نوع بدلات','alert_modules_id'=>2,'active'=>1],
        ['name'=>'حذف نوع بدلات','alert_modules_id'=>2,'active'=>1],
        ['name'=>'ارفاق ملف بصمه','alert_modules_id'=>3,'active'=>1],
        ['name'=>'تعديل متغيرات بصمه','alert_modules_id'=>3,'active'=>1],
        ['name'=>'تعديل متغيرات يدوى','alert_modules_id'=>3,'active'=>1],
        ['name'=>'تعديل اضافى يدوى','alert_modules_id'=>3,'active'=>1],
        ['name'=>'فتح شهر مالى','alert_modules_id'=>4,'active'=>1],
        ['name'=>'اغلاق وارشفه شهر مالى ','alert_modules_id'=>4,'active'=>1],
        ['name'=>'اضافه  جزاء ايام','alert_modules_id'=>4,'active'=>1],
        ['name'=>'تعديل جزاء ايام','alert_modules_id'=>4,'active'=>1],
        ['name'=>'حذف جزاء ايام','alert_modules_id'=>4,'active'=>1],
         ['name'=>'اضافه  غياب ايام','alert_modules_id'=>4,'active'=>1],
        ['name'=>'تعديل غياب ايام','alert_modules_id'=>4,'active'=>1],
        ['name'=>'حذف غياب ايام','alert_modules_id'=>4,'active'=>1],
        ['name'=>'اضافه  خصومات ماليه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'تعديل خصومات ماليه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'حذف خصومات ماليه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'اضافه  سلف شهريه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'تعديل سلف شهريه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'حذف سلف شهريه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'اضافه  سلف مستديمه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'تعديل سلف مستديمه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'حذف سلف مستديمه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'اضافه ايام','alert_modules_id'=>4,'active'=>1],
        ['name'=>'تعديل ايام','alert_modules_id'=>4,'active'=>1],
        ['name'=>'حذف ايام','alert_modules_id'=>4,'active'=>1],
        ['name'=>'اضافه  مكافئات ماليه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'تعديل مكافئات ماليه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'حذف مكافئات ماليه','alert_modules_id'=>4,'active'=>1],
        ['name'=>'اضافه  بدلات متغيره','alert_modules_id'=>4,'active'=>1],
        ['name'=>'تعديل بدلات متغيره','alert_modules_id'=>4,'active'=>1],
        ['name'=>'حذف بدلات متغيره','alert_modules_id'=>4,'active'=>1],
        ['name'=>'اضافه  راتب يدوى','alert_modules_id'=>4,'active'=>1],
        ['name'=>'حذف راتب يدوى','alert_modules_id'=>4,'active'=>1],
        ['name'=>'ايقاف راتب بشكل مؤقت','alert_modules_id'=>4,'active'=>1],
        ['name'=>'الغاء ايقاف راتب بشكل مؤقتا','alert_modules_id'=>4,'active'=>1],
        ['name'=>'ارشفه  راتب بشكل مفرد','alert_modules_id'=>4,'active'=>1],
        ['name'=>'تعديل رصيد السنوى يدويا','alert_modules_id'=>5,'active'=>1],
        ['name'=>'تعديل  رصيد  مرحل يدويا','alert_modules_id'=>5,'active'=>1],
        ['name'=>'تعديل  رصيد سنوى تلقائيا','alert_modules_id'=>5,'active'=>1],
        ['name'=>'اضافه  تحقيقات اداريه','alert_modules_id'=>6,'active'=>1],
        ['name'=>'تعديل  تحقيقات اداريه','alert_modules_id'=>6,'active'=>1],
        ['name'=>'حذف  تحقيقات اداريه','alert_modules_id'=>6,'active'=>1],
        ['name'=>'اضافه مستخدم بالنظام','alert_modules_id'=>7,'active'=>1],
        ['name'=>'تعديل مستخدم بالنظام','alert_modules_id'=>7,'active'=>1],
        ['name'=>'حذف مستخدم بالنظام','alert_modules_id'=>7,'active'=>1],
             
       ]
       );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_movetype');
    }
};
