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
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('helper_id')->nullable()->constrained('users');
            $table->string('title');
            $table->string('category', 100);
            $table->text('description');
            $table->enum('budget_type', ['fixed', 'hourly'])->default('fixed');
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('urgency_level', ['urgent', 'today', 'tomorrow', 'week', 'custom'])->default('custom');
            $table->integer('help_needed_within')->nullable();
            $table->datetime('deadline')->nullable();
            $table->string('location');
            $table->text('address')->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lng', 10, 6)->nullable();
            $table->text('additional_info')->nullable();
            $table->enum('contact_preference', ['message', 'call', 'both'])->default('message');
            $table->enum('privacy', ['public', 'verified', 'invite'])->default('public');
            $table->enum('status', ['pending', 'reviewing', 'accepted', 'in_progress', 'completed', 'cancelled_by_user', 'cancelled_by_system'])->default('pending');
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
