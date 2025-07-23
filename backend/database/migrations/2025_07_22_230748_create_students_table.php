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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->uuid("user_id");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('phone_number');
            $table->string('second_phone_number')->nullable();
            $table->string('parent_phone_number')->nullable();
            $table->enum('preferred_contact_method', ['phone', 'email', 'whatsapp'])->default('whatsapp');
            $table->string('year_of_study')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->decimal('credit', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
