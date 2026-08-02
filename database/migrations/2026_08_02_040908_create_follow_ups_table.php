<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->string('followable_type', 255);
            $table->bigInteger('followable_id');
            $table->enum('type', ['call', 'email', 'meeting', 'task', 'note']);
            $table->date('due_date');
            $table->dateTime('completed_at')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('assigned_to')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};