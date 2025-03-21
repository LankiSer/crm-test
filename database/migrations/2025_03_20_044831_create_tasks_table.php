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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'deferred'])->default('pending');
            $table->date('due_date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Created by
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Assigned to
            $table->nullableMorphs('taskable'); // For polymorphic relationship (can be associated with deal, contact, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
