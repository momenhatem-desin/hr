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
        Schema::create('centers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 225);
            $table->tinyInteger('active')->default(1);
            $table->integer('com_code');
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('governorates_id')->references('id')->on('governorates')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->timestamps();
        });

        DB::table('centers')->insert([
    // القاهرة
    ['name'=>'مدينة نصر','active'=>1,'com_code'=>1,'governorates_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'مصر الجديدة','active'=>1,'com_code'=>1,'governorates_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'المعادي','active'=>1,'com_code'=>1,'governorates_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'حلوان','active'=>1,'com_code'=>1,'governorates_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'شبرا','active'=>1,'com_code'=>1,'governorates_id'=>1,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],

    // الجيزة
    ['name'=>'الدقي','active'=>1,'com_code'=>1,'governorates_id'=>2,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'العجوزة','active'=>1,'com_code'=>1,'governorates_id'=>2,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'الهرم','active'=>1,'com_code'=>1,'governorates_id'=>2,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'فيصل','active'=>1,'com_code'=>1,'governorates_id'=>2,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
    ['name'=>'إمبابة','active'=>1,'com_code'=>1,'governorates_id'=>2,'added_by'=>1,'created_at'=>now(),'updated_at'=>now()],
]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
