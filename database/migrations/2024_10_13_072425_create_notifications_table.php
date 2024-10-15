<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('notifiable_type'); // Polymorphic relation
            $table->unsignedBigInteger('notifiable_id'); // Polymorphic relation
            $table->string('type')->nullable(); // Type field (you can use this to define notification type)
            $table->text('message'); // Message field for the notification content
            $table->boolean('is_read')->default(0); // Marks if the notification has been read
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
            $table->timestamp('read_at')->nullable(); // Custom timestamp for when the notification is read

            // Foreign key constraint for user reference
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
