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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            //name, birthday, phone, position_id, iin, email, company_id, shift, department_id, salary_net, salary_gross
            $table->string('name');
            $table->date('birthday');
            $table->string('phone');
            $table->foreignId('position_id')->constrained();
            $table->string('iin');
            $table->string('email');
            $table->foreignId('company_id')->constrained();
            $table->string('shift');
            $table->foreignId('department_id')->constrained();
            $table->integer('salary_net');
            $table->integer('salary_gross');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
