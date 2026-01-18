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
        Schema::create('vactions_types', function (Blueprint $table) {

            $table->id();
            $table->string('name',100);
            $table->string('variables',1);
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('com_code');
            $table->timestamps();
        });
        

      DB::table('vactions_types')->insert(
[
    ['name'=>'حضور','variables'=>'H','com_code'=>1],
    ['name'=>'راحه اسبوعيه','variables'=>'R','com_code'=>1],
    ['name'=>'سنوى','variables'=>'A','com_code'=>1],
    ['name'=>'بدل راحه','variables'=>'B','com_code'=>1],
    ['name'=>'اجازة رسميه','variables'=>'F','com_code'=>1],
    ['name'=>'غياب بدون أذن','variables'=>'U','com_code'=>1],
    ['name'=>'غياب بدون اجر','variables'=>'D','com_code'=>1],
    ['name'=>'وضع','variables'=>'M','com_code'=>1],
    ['name'=>'ميلاد','variables'=>'L','com_code'=>1],
    ['name'=>'وفاه','variables'=>'V','com_code'=>1],
    ['name'=>'مرضى','variables'=>'S','com_code'=>1],
    ['name'=>'زواج','variables'=>'W','com_code'=>1],
    ['name'=>'اخرى','variables'=>'O','com_code'=>1],
    ['name'=>'عارضه','variables'=>'O','com_code'=>1],
    ['name'=>'اجازة اعتياديه','variables'=>'N','com_code'=>1],
]
);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vactions_types');
    }
};
