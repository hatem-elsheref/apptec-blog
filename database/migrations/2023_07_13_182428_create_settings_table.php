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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('value')->nullable();
            $table->enum('type', ['text', 'email', 'number', 'tel', 'file', 'textarea', 'checkbox', 'radio', 'date', 'time', 'datetime-local']);
            $table->boolean('is_hidden')->default(false);
            $table->string('group');
            $table->unsignedInteger('order')->default(1);
            $table->json('additional')->nullable();
            $table->unique(['key', 'group']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
