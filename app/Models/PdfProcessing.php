<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfProcessing extends Model
{
    use HasFactory;

    protected $table = 'pdf_processing';

    protected $fillable = [
        'file_id',
        'original_name',
        'stored_name',
        'status',
        'extracted_text',
        'n8n_response',
        'processing_result',
        'error_message',
        'sent_to_n8n_at',
        'response_received_at',
    ];

    protected $casts = [
        'n8n_response' => 'array',
        'processing_result' => 'array',
        'sent_to_n8n_at' => 'datetime',
        'response_received_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
}
