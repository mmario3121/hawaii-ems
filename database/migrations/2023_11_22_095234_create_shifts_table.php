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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            //name, start_date, start_time, end_time, break, work_days, vacation_days
            $table->string('name');
            $table->date('start_date');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->integer('break')->nullable();
            $table->integer('work_days');
            $table->integer('vacation_days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
