<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectInvitationsTable extends Migration
{
    public function up()
    {
        Schema::create('project_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('project_id'); // Match int(11) type in `project` table
            $table->string('email');
            $table->string('status')->default('pending');
            $table->string('token')->nullable();
            $table->timestamps();

            // Correct table name for foreign key reference
            $table->foreign('project_id')->references('id')->on('project')->onDelete('cascade');

            // Ensure uniqueness of pending invitations for each project-email combination
            $table->unique(['project_id', 'email', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_invitations');
    }
}