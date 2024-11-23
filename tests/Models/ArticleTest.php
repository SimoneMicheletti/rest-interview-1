<?php

namespace Tests\Models;

use App\Models\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testGetEffectiveTitleWithTitle()
    {
        $data = ['title' => 'Titolo Articolo', 'story_title' => 'Titolo Storia'];
        $article = new Article($data);

        $this->assertEquals('Titolo Articolo', $article->getEffectiveTitle());
    }

    public function testGetEffectiveTitleWithStoryTitle()
    {
        $data = ['title' => null, 'story_title' => 'Titolo Storia'];
        $article = new Article($data);

        $this->assertEquals('Titolo Storia', $article->getEffectiveTitle());
    }

    public function testGetEffectiveTitleWithNoTitle()
    {
        $data = ['title' => null, 'story_title' => null];
        $article = new Article($data);

        $this->assertNull($article->getEffectiveTitle());
    }
}
