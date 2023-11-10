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
        Schema::create('workdays', function (Blueprint $table) {
            $table->id();//date, employee_id, workhours, overtime, sickleave, vacation, holiday, comment
            $table->date('date');
            $table->foreignId('employee_id')->constrained();
            $table->integer('workhours')->default(0);
            $table->integer('overtime')->default(0);
            $table->boolean('sickleave')->default(0);
            $table->boolean('vacation')->default(0);
            $table->boolean('holiday')->default(0);
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workdays');
    }
};
