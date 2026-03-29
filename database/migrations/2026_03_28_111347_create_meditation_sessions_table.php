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
        Schema::create('meditation_sessions', function (Blueprint $table) {
            $table->id();
            // user_id batayega ki yeh session kis user ka hai
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->date('session_date'); // Kis din dhyan kiya
            $table->integer('duration_minutes'); // Kitne minute kiya
            $table->text('notes')->nullable(); // Optional notes
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meditation_sessions');
    }
};
