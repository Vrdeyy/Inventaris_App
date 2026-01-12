<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('location');
            $table->integer('quantity')->default(0);
            $table->enum('condition', ['baik', 'rusak', 'hilang'])->default('baik');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained('users'); // Created by (or last updated by? better track separately but for now this is 'owner' or 'creator')
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
