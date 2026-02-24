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
        Schema::create('employees_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('employees_code')->comment('كود الموظف ');
            $table->string("title", 300);
            $table->text("task_content");
            $table->text("task_content_reply");
            $table->tinyInteger("is_confirm_done_form_emp")->nullable()->default(0)->comment("هل تم تاكيد انتهاء التاسك مع الموظف ");
             $table->integer('is_archived')->nullable()->comment("حالة الارشفة");
            $table->dateTime('archived_at')->nullable()->comment("تاريخ الارشفة");   
            $table->foreignId('archived_by')->nullable()->references('id')->on('admins')->onUpdate('cascade'); 
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
        Schema::dropIfExists('employees_tasks');
    }
};
