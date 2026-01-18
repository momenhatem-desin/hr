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
        Schema::create('alerts_system_monitoring', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('alert_modules_id');
             $table->unsignedBigInteger('alert_movetype_id');
            $table->foreign('alert_modules_id')->references('id')->on('alert_modules')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('alert_movetype_id')->references('id')->on('alert_movetype')->onDelete('cascade')->onUpdate('cascade');
            $table->string("content",500);
            $table->bigInteger("foreign_key_table_id")->nullable();
            $table->integer('employees_code')->nullable()->comment('كود الموظف  ');
            $table->tinyInteger("is_marked")->default(0)->comment('تحديد');
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->integer('com_code');
            $table->date("date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts_system_monitoring');
    }
};
