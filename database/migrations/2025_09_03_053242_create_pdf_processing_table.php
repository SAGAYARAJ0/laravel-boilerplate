<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdfProcessingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_processing', function (Blueprint $table) {
            $table->id();
            $table->string('file_id')->unique();
            $table->string('original_name');
            $table->string('stored_name');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->longText('extracted_text')->nullable();
            $table->json('n8n_response')->nullable();
            $table->json('processing_result')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_to_n8n_at')->nullable();
            $table->timestamp('response_received_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdf_processing');
    }
}
