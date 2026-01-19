<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // ❌ Purana foreign key hatao
            $table->dropForeign(['user_id']);

            // ✅ Naya foreign key with CASCADE
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // ❌ Cascade wala FK hatao
            $table->dropForeign(['user_id']);

            // 🔁 Normal FK (no cascade)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });
    }
};
