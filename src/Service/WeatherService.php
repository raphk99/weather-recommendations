<?php
// src/Service/WeatherService.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    private $client;
    // private $apiKey;

    public function __construct(HttpClientInterface $client/*, string $apiKey*/)
    {
        $this->client = $client;
        // $this->apiKey = 'YOUR_API_KEY'
    }

    public function getForecast(string $city, ?string $date = null): array
    {
        $url = "http://api.weatherapi.com/v1/forecast.json?key=e04e91db1aa34d6796b125931240805&q={$city}&days=2";

        $response = $this->client->request('GET', $url);

        // ya peut etre d'autres types d'erreurs au quel cas passer le message d'erreur en parametre au controller
        if ($response->getStatusCode() === 400) {
            return [];
        }
        $data = $response->toArray();

        
        // Extract the forecast for today or tomorrow
        $forecastDay = ($date === "tomorrow") ? 1 : 0;
        
        return $data['forecast']['forecastday'][$forecastDay];
    }
}
?>