<?php

namespace App\Controllers;

use App\Services\CacheManager;
use App\Services\API\Article\ArticleApiClient;

class ArticleController
{
    private CacheManager $cacheManager;
    private ArticleApiClient $articleApiClient;

    public function __construct(CacheManager $cacheManager = null, ArticleApiClient $articleApiClient = null)
    {
        $this->cacheManager = $cacheManager ?? new CacheManager();
        $this->articleApiClient = $articleApiClient ?? new ArticleApiClient();
    }

    public function getArticlesTitlesByAuthor(string $author): array
    {
        $cachedArticles = $this->cacheManager->get($author);

        if ($cachedArticles) {
            return $cachedArticles;
        }

        $articles = $this->articleApiClient->fetchArticles($author);

        $clean_articles = array_filter(array_map(
            fn($article) => $article->getEffectiveTitle(),
            $articles
        ));

        if (!empty($clean_articles)) {
            $this->cacheManager->set($author, $clean_articles);
        }

        return $clean_articles;
    }
}
