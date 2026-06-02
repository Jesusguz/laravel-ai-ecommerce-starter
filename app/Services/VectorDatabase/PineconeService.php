<?php

namespace App\Services\VectorDatabase;

use Illuminate\Support\Facades\Http;
use Exception;

class PineconeService
{
    /**
     * Upserts (Update or Insert) a vector into the Pinecone index.
     *
     * @param string $id The unique product SKU.
     * @param array<float> $vector The mathematical embedding.
     * @param array $metadata Extra attributes for filtering (price, stock).
     */
    public function upsert(string $id, array $vector, array $metadata = []): void
    {
        $apiKey = config('services.pinecone.key');
        $host = config('services.pinecone.host');

        if (!$apiKey || !$host) {
            throw new Exception("Pinecone configuration is missing in the .env file.");
        }

        $url = "https://{$host}/vectors/upsert";

        $response = Http::withHeaders([
            'Api-Key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'vectors' => [
                [
                    'id' => (string) $id,
                    'values' => $vector,
                    'metadata' => $metadata
                ]
            ]
        ]);

        if ($response->failed()) {
            throw new Exception("Pinecone Upsert API Error: " . $response->body());
        }
    }

    /**
     * Queries the Pinecone index for vectors similar to the provided embedding.
     *
     * @param array<float> $vector The embedded user query.
     * @param int $topK Number of most relevant results to return.
     * @return array The matched vectors and their metadata.
     */
    public function query(array $vector, int $topK = 3): array
    {
        $apiKey = config('services.pinecone.key');
        $host = config('services.pinecone.host');

        $url = "https://{$host}/query";

        $response = Http::withHeaders([
            'Api-Key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'vector' => $vector,
            'topK' => $topK,
            'includeMetadata' => true
        ]);

        if ($response->failed()) {
            throw new Exception("Pinecone Query API Error: " . $response->body());
        }

        return $response->json('matches') ?? [];
    }
}