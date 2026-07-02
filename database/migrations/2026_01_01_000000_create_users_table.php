<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class CreateUsersTable
{
    public function up(): void
    {
        Capsule::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('user_id')->nullable();
            $table->string('action')->nullable();
            $table->text('poll_options')->nullable();
            $table->text('poll_question')->nullable();
            $table->string('selected_channel')->nullable();
            $table->string('scheduled_time')->nullable();
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('users');
    }
}
