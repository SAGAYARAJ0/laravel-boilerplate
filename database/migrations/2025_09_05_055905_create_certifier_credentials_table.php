<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certifier_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_template_id')->constrained()->onDelete('cascade');
            $table->string('certifier_credential_id')->unique();
            $table->string('recipient_name');
            $table->string('recipient_email');
            $table->json('credential_data'); // Store all credential data from Certifier
            $table->enum('status', ['created', 'issued', 'sent', 'failed'])->default('created');
            $table->string('certifier_url')->nullable(); // URL to view credential on Certifier
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->json('error_details')->nullable(); // Store error information if any
            $table->timestamps();
            
            $table->index(['certificate_template_id', 'status']);
            $table->index('recipient_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certifier_credentials');
    }
};
