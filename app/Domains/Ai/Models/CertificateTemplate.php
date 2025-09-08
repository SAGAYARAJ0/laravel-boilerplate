<?php

namespace App\Domains\Ai\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CertificateTemplate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'template_data',
        'background_image',
        'is_active',
        'width',
        'height',
        'orientation',
        'certifier_design_id',
        'template_source',
        'certifier_group_id',
        'certifier_metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'template_data' => 'array',
        'is_active' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
        'certifier_metadata' => 'array'
    ];

    /**
     * Get the background image URL
     */
    public function getBackgroundImageUrlAttribute()
    {
        if ($this->background_image) {
            return Storage::url($this->background_image);
        }
        return null;
    }

    /**
     * Get template dimensions
     */
    public function getDimensionsAttribute()
    {
        return [
            'width' => $this->width ?? 1200,
            'height' => $this->height ?? 800
        ];
    }

    /**
     * Scope for active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get generated certificates for this template
     */
    public function generatedCertificates()
    {
        return $this->hasMany(GeneratedCertificate::class);
    }

    /**
     * Get Certifier credentials for this template
     */
    public function certifierCredentials()
    {
        return $this->hasMany(CertifierCredential::class);
    }

    /**
     * Check if template is from Certifier
     */
    public function isCertifierTemplate()
    {
        return $this->template_source === 'certifier';
    }

    /**
     * Scope for Certifier templates
     */
    public function scopeCertifier($query)
    {
        return $query->where('template_source', 'certifier');
    }

    /**
     * Scope for local templates
     */
    public function scopeLocal($query)
    {
        return $query->where('template_source', 'local');
    }

    /**
     * Get template variables from template_data
     */
    public function getTemplateVariablesAttribute()
    {
        if (!$this->template_data || !isset($this->template_data['objects'])) {
            return [];
        }

        $variables = [];
        foreach ($this->template_data['objects'] as $object) {
            if (isset($object['type']) && $object['type'] === 'text' && isset($object['text'])) {
                // Extract variables like {{variable_name}}
                preg_match_all('/\{\{([^}]+)\}\}/', $object['text'], $matches);
                if (!empty($matches[1])) {
                    $variables = array_merge($variables, $matches[1]);
                }
            }
        }

        return array_unique($variables);
    }
}
