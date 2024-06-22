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
        Schema::create('shared_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id');
            $table->foreignIdFor(\App\Models\User::class);
            $table->timestamps();

            $table->unique(['list_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_lists');
    }
};
