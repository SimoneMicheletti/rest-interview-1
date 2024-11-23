<?php

namespace Tests\Controllers;

use App\Controllers\ArticleController;
use App\Models\Article;
use App\Services\CacheManager;
use App\Services\API\Article\ArticleApiClient;
use PHPUnit\Framework\TestCase;

class ArticleControllerTest extends TestCase
{
    public function testGetArticlesTitlesByAuthorFromCache()
    {
        $author = 'test_author';
        $cachedArticles = ['Titolo Articolo 1', 'Titolo Articolo 2'];

        $cacheManagerMock = $this->createMock(CacheManager::class);
        $cacheManagerMock->method('get')->with($author)->willReturn($cachedArticles);

        $articleApiClientMock = $this->createMock(ArticleApiClient::class);

        $controller = new ArticleController($cacheManagerMock, $articleApiClientMock);
        $result = $controller->getArticlesTitlesByAuthor($author);

        $this->assertEquals($cachedArticles, $result);
    }

    public function testGetArticlesTitlesByAuthorFromApi()
    {
        $author = 'test_author';
        $apiArticles = [
            new Article(['title' => 'Titolo Articolo 1', 'author' => $author]),
            new Article(['title' => 'Titolo Articolo 2', 'author' => $author]),
            new Article(['title' => '', 'author' => $author]),
            new Article(['title' => null, 'author' => $author]),
            new Article(['title' => false, 'author' => $author]),
            new Article([]),
        ];
        $expectedTitles = ['Titolo Articolo 1', 'Titolo Articolo 2'];

        $cacheManagerMock = $this->createMock(CacheManager::class);
        $cacheManagerMock->method('get')->with($author)->willReturn(null);
        $cacheManagerMock->expects($this->once())->method('set')->with($author, $expectedTitles);

        $articleApiClientMock = $this->createMock(ArticleApiClient::class);
        $articleApiClientMock->method('fetchArticles')->with($author)->willReturn($apiArticles);

        $controller = new ArticleController($cacheManagerMock, $articleApiClientMock);
        $result = $controller->getArticlesTitlesByAuthor($author);
        $this->assertEquals($expectedTitles, $result);
    }
}
