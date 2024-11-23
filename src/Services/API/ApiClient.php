<?php

namespace App\Services\API;

class ApiClient
{
    public function get(string $url): array|bool
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, (int) ($_ENV['API_TIMEOUT'] ?? 30));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "Errore cURL: " . curl_error($ch);
            return false;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            echo "Errore HTTP: " . $httpCode;
            return false;
        }

        $decodedResponse = json_decode($response, true);
        return $decodedResponse ?: [];
    }
}
