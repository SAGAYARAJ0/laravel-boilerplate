<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneratedCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generated_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_template_id')->constrained()->onDelete('cascade');
            $table->string('recipient_name');
            $table->string('recipient_email')->nullable();
            $table->string('course_name');
            $table->date('completion_date')->nullable();
            $table->json('certificate_data');
            $table->string('file_path')->nullable();
            $table->string('file_type')->default('pdf');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamps();

            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['certificate_template_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generated_certificates');
    }
}
