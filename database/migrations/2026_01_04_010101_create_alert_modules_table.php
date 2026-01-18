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
        Schema::create('alert_modules', function (Blueprint $table) {
           $table->id();
            $table->string('name',150);
            $table->string('name_en',150)->nullable();
         $table->tinyInteger('active')->default(1);
         
        });

         DB::table('alert_modules')->insert(
       [
        ['name'=>'الضبط العام','active'=>1],
        ['name'=>'شئون الموظفين','active'=>1],
        ['name'=>'سجل الحضور','active'=>1],
        ['name'=>'الاجازات','active'=>1],
        ['name'=>'التحقيقات الاداريه','active'=>1],
        ['name'=>'المراقبه','active'=>1],
        ['name'=>'الصلاحيات','active'=>1]
  

       ]
       );
  }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_modules');
    }
};
