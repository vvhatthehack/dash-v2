<?php

// database/migrations/xxxx_xx_xx_create_session_histories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('session_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('last_activity');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('timezone')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('session_histories');
    }
}
