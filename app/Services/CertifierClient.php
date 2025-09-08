<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Exception;

class CertifierClient
{
    protected string $baseUrl;
    protected array $headers;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.certifier.base_url', env('CERTIFIER_API_URL')), '/');
        $this->headers = [
            'Authorization' => 'Bearer ' . env('CERTIFIER_TOKEN'),
            'Certifier-Version' => env('CERTIFIER_VERSION', '2022-10-26'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Get list of designs with pagination
     */
    public function getDesigns(array $query = []): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->get("{$this->baseUrl}/designs", $query)
                ->throw();

            return $response->json();
        } catch (Exception $e) {
            throw new Exception("Failed to fetch designs: " . $e->getMessage());
        }
    }

    /**
     * Get a specific design by ID
     */
    public function getDesign(string $id): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->get("{$this->baseUrl}/designs/{$id}")
                ->throw();

            return $response->json();
        } catch (Exception $e) {
            throw new Exception("Failed to fetch design {$id}: " . $e->getMessage());
        }
    }

    /**
     * Create a group bound to a design
     */
    public function createGroup(array $payload): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/groups", $payload)
                ->throw();

            return $response->json();
        } catch (Exception $e) {
            throw new Exception("Failed to create group: " . $e->getMessage());
        }
    }

    /**
     * Create a credential
     */
    public function createCredential(array $payload): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/credentials", $payload)
                ->throw();

            return $response->json();
        } catch (Exception $e) {
            throw new Exception("Failed to create credential: " . $e->getMessage());
        }
    }

    /**
     * Issue a credential
     */
    public function issueCredential(string $credentialId): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/credentials/{$credentialId}/issue")
                ->throw();

            return $response->json();
        } catch (Exception $e) {
            throw new Exception("Failed to issue credential {$credentialId}: " . $e->getMessage());
        }
    }

    /**
     * Send a credential
     */
    public function sendCredential(string $credentialId, array $payload = []): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/credentials/{$credentialId}/send", $payload)
                ->throw();

            return $response->json();
        } catch (Exception $e) {
            throw new Exception("Failed to send credential {$credentialId}: " . $e->getMessage());
        }
    }

    /**
     * Create, issue, and send credential in one call
     */
    public function createIssueSend(array $payload): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/credentials/create-issue-send", $payload)
                ->throw();

            return $response->json();
        } catch (Exception $e) {
            throw new Exception("Failed to create, issue, and send credential: " . $e->getMessage());
        }
    }

    /**
     * Test API connection
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->get("{$this->baseUrl}/designs", ['limit' => 1]);

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }
}
