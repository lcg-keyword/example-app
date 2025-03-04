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
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('user');
            $table->timestamps();
            $table->text('sql')->nullable();
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
