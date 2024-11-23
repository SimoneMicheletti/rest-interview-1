<?php

namespace App\Services\API\Article;

use App\Services\API\ApiClient;
use App\Models\Article;

class ArticleApiClient
{
    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient = null)
    {
        $this->apiClient = $apiClient ?? new ApiClient();
    }

    public function fetchArticles(string $author): array
    {
        $articles = [];
        $page = 1;

        do {
            $url = "{$_ENV['API_BASE_URL']}/articles?page={$page}";
            if (!empty($author)) {
                $url .= '&author=' . urlencode($author);
            }

            $response = $this->apiClient->get($url);

            if (!$response || !isset($response['data']) || !isset($response['total_pages'])) {
                echo "Errore nella risposta dell'API: ";
                return $articles;
            }

            foreach ($response['data'] as $articleData) {
                $articles[] = new Article($articleData);
            }

            $page++;

        } while ($page <= $response['total_pages']);

        return $articles;
    }
}
