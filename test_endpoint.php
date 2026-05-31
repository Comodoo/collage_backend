<?php
$client = new \GuzzleHttp\Client();
try {
    $response = $client->post('http://localhost:8000/api/registrations/1/approve', [
        'headers' => [
            'Accept' => 'application/json',
            // Wait, I need an auth token!
            // I'll just skip the HTTP call and do a direct controller call using Tinker.
        ]
    ]);
} catch (\Exception $e) {
    echo $e->getMessage();
}
