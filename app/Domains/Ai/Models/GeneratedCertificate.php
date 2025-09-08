<?php

namespace App\Domains\Ai\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class GeneratedCertificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'certificate_template_id',
        'recipient_name',
        'recipient_email',
        'course_name',
        'completion_date',
        'certificate_data',
        'file_path',
        'file_type',
        'status',
        'generated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'certificate_data' => 'array',
        'completion_date' => 'date',
        'generated_by' => 'integer'
    ];

    /**
     * Get the certificate template
     */
    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    /**
     * Get the user who generated this certificate
     */
    public function generatedBy()
    {
        return $this->belongsTo(\App\Domains\Auth\Models\User::class, 'generated_by');
    }

    /**
     * Get the certificate file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::disk('public')->url($this->file_path);
        }
        return null;
    }

    /**
     * Scope for completed certificates
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending certificates
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get certificate unique identifier
     */
    public function getCertificateIdAttribute()
    {
        return 'CERT-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
