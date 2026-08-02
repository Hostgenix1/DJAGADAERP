<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->string('communicable_type', 255);
            $table->bigInteger('communicable_id');
            $table->enum('type', ['call', 'whatsapp', 'email', 'meeting', 'note']);
            $table->enum('direction', ['inbound', 'outbound'])->nullable();
            $table->string('subject', 255)->nullable();
            $table->text('body')->nullable();
            $table->foreignId('contact_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->dateTime('occurred_at');
            $table->timestamps();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};