<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$client = new Client([
    'timeout' => 10,
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ]
]);

// Test reliable websites
$testSites = [
    'https://www.forbes.com/' => 'Forbes',
    'https://www.bbc.com/news' => 'BBC News',
    'https://www.reuters.com/' => 'Reuters',
    'https://httpbin.org/html' => 'HTTPBin (Test)',
];

echo "🔍 Testing website accessibility...\n\n";

foreach ($testSites as $url => $name) {
    echo "Testing: {$name} ({$url})\n";
    
    try {
        $response = $client->get($url);
        $statusCode = $response->getStatusCode();
        $contentLength = strlen($response->getBody());
        
        echo "  ✅ Status: {$statusCode}, Content: {$contentLength} bytes\n";
        
        // Test if we can find articles/content
        $html = (string) $response->getBody();
        $dom = new DOMDocument();
        if (!empty($html)) {
            @$dom->loadHTML($html);
        }
        $xpath = new DOMXPath($dom);
        
        // Look for common article selectors
        $selectors = [
            '//article',
            '//h1', 
            '//h2', 
            '//h3',
            '//p'
        ];
        
        $elementsFound = 0;
        foreach ($selectors as $selector) {
            $elements = $xpath->query($selector);
            $elementsFound += $elements->length;
        }
        
        echo "  📄 HTML elements found: {$elementsFound}\n";
        
    } catch (RequestException $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    } catch (Exception $e) {
        echo "  ❌ General Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "Test completed!\n";