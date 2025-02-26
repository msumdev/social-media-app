<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

/**
 * Class BaseService
 * @package App\Services
 */
class ElasticSearchService
{
    /**
     * @param string $indexName
     * @return void
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function refreshIndex(string $indexName): void
    {
        $host = config('database.connections.elasticsearch.hosts')[0];
        $url = $host . "/$indexName/_refresh";

        Http::withBasicAuth(
            config('database.connections.elasticsearch.username'),
            config('database.connections.elasticsearch.password')
        )->get($url);
    }

    /**
     * Deletes indices matching the wildcard pattern.
     *
     * @param string $indexPattern
     * @return JsonResponse
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function deleteIndex(string $indexPattern): JsonResponse
    {
        $host = config('database.connections.elasticsearch.hosts')[0];
        $url = $host . "/$indexPattern";

        $response = Http::withBasicAuth(
            config('database.connections.elasticsearch.username'),
            config('database.connections.elasticsearch.password')
        )->delete($url);

        if ($response->failed()) {
            $response->throw();
        }

        return response()->json([
            'message' => 'Indices deleted successfully.',
            'response' => $response->json(),
        ]);
    }

    /**
     * Updates the Elasticsearch cluster setting to set action.destructive_requires_name to true.
     *
     * @return JsonResponse
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function updateClusterSettings(): JsonResponse
    {
        $host = config('database.connections.elasticsearch.hosts')[0];
        $url = $host . "/_cluster/settings";

        $response = Http::withBasicAuth(
            config('database.connections.elasticsearch.username'),
            config('database.connections.elasticsearch.password')
        )->put($url, [
            'persistent' => [
                'action.destructive_requires_name' => false,
            ],
        ]);

        if ($response->failed()) {
            $response->throw();
        }

        return response()->json([
            'message' => 'Cluster settings updated successfully.',
            'response' => $response->json(),
        ]);
    }
}
