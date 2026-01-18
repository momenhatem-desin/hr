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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name',225);
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
            $table->integer('com_code');
        });

        DB::table('languages')->insert([
    [
        'name' => 'العربية',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'الإنجليزية',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'الفرنسية',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'الألمانية',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'الإيطالية',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'الإسبانية',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
