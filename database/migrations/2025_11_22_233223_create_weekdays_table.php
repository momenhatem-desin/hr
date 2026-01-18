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
        Schema::create('weekdays', function (Blueprint $table) {
            $table->id();
             $table->string('name',50);
            $table->string('name_en',50);
            
        });

         DB::table('weekdays')->insert(
       [
        ['name'=>'السبت','name_en'=>'saturday'],
        ['name'=>'الاحد','name_en'=>'sunday'],
        ['name'=>'الاثنين','name_en'=>'monday'],
        ['name'=>'الثلاثاء','name_en'=>'tuesday'],
        ['name'=>'الاربعاء','name_en'=>'wednesday'],
        ['name'=>'الخميس','name_en'=>'thursday'],
        ['name'=>'الجمعه','name_en'=>'friday'],

       ]
          );
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekdays');
    }
};
