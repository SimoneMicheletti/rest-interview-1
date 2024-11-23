<?php

namespace App\Services;

class CacheManager
{
    private string $cacheDir;
    private bool $cacheEnabled;
    private bool $expirationEnabled;
    private int $cacheExpiration;

    public function __construct()
    {
        $this->cacheDir = $_ENV['CACHE_DIR'] ?: 'cache/';
        $this->cacheEnabled = $_ENV['CACHE_ENABLED'] === 'true';
        $this->expirationEnabled = $_ENV['CACHE_EXPIRATION'] === 'true';
        $this->cacheExpiration = (int) $_ENV['CACHE_EXPIRATION_MINUTES'] ?: 60;

        if ($this->cacheEnabled && !is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function get(string $author): ?array
    {
        if (!$this->cacheEnabled) {
            return null;
        }

        $authorCacheDir = $this->getAuthorCacheDir($author);

        if (!is_dir($authorCacheDir)) {
            return null;
        }

        $cacheTimeFile = $this->getCacheTimeFile($authorCacheDir);
        if ($this->expirationEnabled && (!file_exists($cacheTimeFile) || $this->isCacheExpired($cacheTimeFile))) {
            $this->clear($authorCacheDir);
            return null;
        }

        $cacheFile = $this->getCacheFile($authorCacheDir);
        return file_exists($cacheFile) ? json_decode(file_get_contents($cacheFile), true) : null;
    }

    public function set(string $author, array $data): void
    {
        if (!$this->cacheEnabled) {
            return;
        }

        $authorCacheDir = $this->getAuthorCacheDir($author);

        if (!is_dir($authorCacheDir)) {
            mkdir($authorCacheDir, 0777, true);
        }

        file_put_contents($this->getCacheTimeFile($authorCacheDir), time());
        file_put_contents($this->getCacheFile($authorCacheDir), json_encode($data));
    }

    private function isCacheExpired(string $cacheTimeFile): bool
    {
        $cacheTime = (int) file_get_contents($cacheTimeFile);
        $expirationTime = $cacheTime + ($this->cacheExpiration * 60);
        return time() > $expirationTime;
    }

    private function clear(string $authorCacheDir): void
    {
        array_map('unlink', glob("$authorCacheDir/*"));
        rmdir($authorCacheDir);
    }

    private function getAuthorCacheDir(string $author): string
    {
        return $this->cacheDir . md5($author) . '/';
    }

    private function getCacheTimeFile(string $authorCacheDir): string
    {
        return $authorCacheDir . 'cache_time';
    }

    private function getCacheFile(string $authorCacheDir): string
    {
        return $authorCacheDir . 'data.json';
    }
}