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
        Schema::create('governorates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 225);
            $table->tinyInteger('active')->default(1);
            $table->integer('com_code');
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('countires_id')->references('id')->on('countires')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->timestamps();
        });

        DB::table('governorates')->insert([
    ['name'=>'القاهرة','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الجيزة','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الإسكندرية','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الدقهلية','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'البحر الأحمر','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'البحيرة','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الفيوم','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الغربية','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الإسماعيلية','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'المنوفية','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'المنيا','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'القليوبية','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الوادي الجديد','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'السويس','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'اسوان','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'اسيوط','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'بني سويف','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'بورسعيد','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'دمياط','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الشرقية','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'جنوب سيناء','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'شمال سيناء','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'كفر الشيخ','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'مطروح','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الأقصر','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'قنا','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'سوهاج','active'=>1,'com_code'=>1,'countires_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governorates');
    }
};
