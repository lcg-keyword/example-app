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
        Schema::create('sql_execution_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->timestamps();
            $table->string('sql');
            $table->text('error')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sql_execution_logs');
    }
};
