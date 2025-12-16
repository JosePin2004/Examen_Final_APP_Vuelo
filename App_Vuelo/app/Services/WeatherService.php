<?php

namespace App\Services;

use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log; 

class WeatherService
{
    private string|null $apiKey; 
    private string $baseUrl; 

    public function __construct(?string $apiKey = null, ?string $baseUrl = null) // Constructor para iniciar el servicio 
    {
        $this->apiKey = $apiKey ?? config('services.openweather.key') ?? env('OPENWEATHER_API_KEY'); //clave de la api
        $this->baseUrl = rtrim($baseUrl ?? config('services.openweather.base_url') ?? env('OPENWEATHER_BASE_URL', 'https://api.openweathermap.org/data/2.5'), '/'); //url base de la api
        
        Log::debug('WeatherService initialized', [ //configuracion inicial del servicio
            'key_present' => !empty($this->apiKey), 
            'key_length' => strlen($this->apiKey ?? ''), 
            'baseUrl' => $this->baseUrl, //url 
        ]);
    }

    public function isConfigured(): bool // Verifica si la api esta configurada
    {
        return !empty($this->apiKey); //ver si la clave de la api no esta vacia
    }

    // Funcion para obtener el clima actual de una ciudad
    public function getCurrentByCity(string $city): ?array
    {
        if (!$this->isConfigured()) { 
            Log::warning('WeatherService: API key not configured'); //api no configurada
            return ['error' => 'API key not configured'];
        }

        $queryCity = $this->normalizeCity($city); //ciudad sin datos extraños como codigos de aeropuerto
        Log::debug('WeatherService: Requesting weather', ['original_city' => $city, 'normalized_city' => $queryCity]); //solicitud del clima

        try {
            $response = Http::acceptJson()->withoutVerifying()->get("{$this->baseUrl}/weather", [ //llamada a la api
                'q' => $queryCity,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'es',
            ]);

            Log::debug('WeatherService: API response', ['status' => $response->status()]); //respuesta de la api
 
            if (!$response->successful()) { //respuesta no es exitosa
                Log::warning('WeatherService: API error', ['status' => $response->status(), 'body' => $response->body()]);
                return ['error' => $response->json('message') ?? 'OpenWeather error'];
            }

            $data = $response->json(); 

            return [
                'city' => data_get($data, 'name'),
                'country' => data_get($data, 'sys.country'),
                'temp_c' => data_get($data, 'main.temp'),
                'feels_like_c' => data_get($data, 'main.feels_like'),
                'temp_min_c' => data_get($data, 'main.temp_min'),
                'temp_max_c' => data_get($data, 'main.temp_max'),
                'humidity' => data_get($data, 'main.humidity'),
                'pressure' => data_get($data, 'main.pressure'),
                'wind_speed_ms' => data_get($data, 'wind.speed'),
                'description' => data_get($data, 'weather.0.description'),
                'icon' => data_get($data, 'weather.0.icon'),
                'timestamp' => data_get($data, 'dt'),
            ];
        } catch (\Exception $e) { 
            Log::error('WeatherService: Exception', ['error' => $e->getMessage()]); 
            return ['error' => $e->getMessage()];
        }
    }

    private function normalizeCity(string $city): string
    {
        // Eliminar cualquier texto entre paréntesis y espacios adicionales
        $clean = preg_replace('/\s*\(.*?\)\s*/', '', $city);
        $clean = trim($clean);
        return $clean ?: $city;
    }
}

