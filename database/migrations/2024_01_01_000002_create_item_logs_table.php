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
        Schema::create('item_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Who made the change
            $table->string('action')->nullable(); // create, update, delete (optional, can infer)
            $table->string('old_condition')->nullable();
            $table->string('new_condition')->nullable();
            $table->integer('old_quantity')->nullable();
            $table->integer('new_quantity')->nullable();
            $table->text('description')->nullable(); // Catatan
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_logs');
    }
};
