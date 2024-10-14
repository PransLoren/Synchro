<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->time('submission_time');
            $table->date('submission_date');
            $table->string('document_file')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Foreign key reference
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
