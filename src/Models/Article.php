<?php

namespace App\Models;

class Article
{
    public ?string $title;
    public ?string $url;
    public ?string $author;
    public ?int $numComments;
    public ?int $storyId;
    public ?string $storyTitle;
    public ?string $storyUrl;
    public ?int $parentId;
    public \DateTime $createdAt;

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->author = $data['author'] ?? null;
        $this->numComments = $data['num_comments'] ?? null;
        $this->storyId = $data['story_id'] ?? null;
        $this->storyTitle = $data['story_title'] ?? null;
        $this->storyUrl = $data['story_url'] ?? null;
        $this->parentId = $data['parent_id'] ?? null;
    }

    public function getEffectiveTitle(): ?string
    {
        return $this->title ?? $this->storyTitle ;
    }
}
