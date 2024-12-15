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
            $table->string('name');
            $table->string('father_phone');
            $table->string('mather_phone');
            $table->decimal('longitude', 10, 8);
            $table->decimal('latitude', 10, 8);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status',['attendee','absent','Moved_to','Transferred_from'])->default('attendee');
            $table->timestamps();
            $table->softDeletes();
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
