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
        Schema::create('blood_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 225);
            $table->tinyInteger('active')->default(1);
            $table->integer('com_code');
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->timestamps();
        });

        DB::table('blood_groups')->insert([
    [
        'name' => 'A+',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'A-',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'B+',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'B-',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'AB+',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'AB-',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'O+',
        'active' => 1,
        'com_code' => 1,
        'added_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'O-',
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
        Schema::dropIfExists('blood_groups');
    }
};
