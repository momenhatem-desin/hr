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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('email',100)->nullable();
            $table->string('username',100);
            $table->string('password',225);
            $table->tinyInteger('is_master_admin')->default(0)->comment("هل هو ادمن رئيسى");
            $table->tinyInteger('permission_roles_id')->nullable()->comment("رقم دور الصلاحيه فى حاله انه ليس ادمن رئيسى");
            $table->tinyInteger('is_programer')->default(0)->comment("المبرمج فقط");
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->tinyInteger('active');
            $table->date('date');
            $table->string('image',250)->nullable();
            $table->timestamps();
            $table->integer('com_code');
        });
        DB::table('admins')->insert(
            [
             ['name'=>'admin',
             'email'=>'test@gmail.com',
             'username'=>'admin',
             'is_master_admin'=>1,
             'password'=>bcrypt("admin"),
             'active'=>1,
             'date'=>date("Y-m-d"),
             'com_code'=>1,
             'added_by'=>1,
             'updated_by'=>1


            ],
          
        
            ]
                
     
            
          
           );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
