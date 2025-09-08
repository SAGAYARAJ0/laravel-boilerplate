<?php

namespace App\Domains\Ai\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertifierCredential extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'certificate_template_id',
        'certifier_credential_id',
        'recipient_name',
        'recipient_email',
        'credential_data',
        'status',
        'certifier_url',
        'issued_at',
        'sent_at',
        'error_details'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'credential_data' => 'array',
        'error_details' => 'array',
        'issued_at' => 'datetime',
        'sent_at' => 'datetime'
    ];

    /**
     * Get the certificate template that owns this credential
     */
    public function certificateTemplate()
    {
        return $this->belongsTo(CertificateTemplate::class);
    }

    /**
     * Scope for issued credentials
     */
    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    /**
     * Scope for sent credentials
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed credentials
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if credential is issued
     */
    public function isIssued()
    {
        return $this->status === 'issued';
    }

    /**
     * Check if credential is sent
     */
    public function isSent()
    {
        return $this->status === 'sent';
    }

    /**
     * Check if credential failed
     */
    public function hasFailed()
    {
        return $this->status === 'failed';
    }
}
