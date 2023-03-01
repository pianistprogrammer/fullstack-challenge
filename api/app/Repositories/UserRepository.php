<?php
namespace App\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


interface UserRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function getWeatherData($latitude, $longitude);
}

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(array $data, $id)
    {
        $user = User::findOrFail($id);

        $user->update($data);

        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        $user->delete();
    }
    public function getWeatherData($latitude, $longitude)
    {   $cacheKey = "weather_{$latitude}_{$longitude}";
        $data = Cache::get($cacheKey);
        $apiKey = env('OPENWEATHERMAP_API_KEY');
        $apiBaseUrl = env('OPENWEATHERMAP_BASE_API');

        if (!$data){
            $url = "$apiBaseUrl/onecall?lat=$latitude&lon=$longitude&appid=$apiKey";
            $response = Http::timeout(0.5)->get($url); // To ensure that the internal API request to retrieve weather data takes no longer than 500ms,

            if ($response->ok()) {
                $data = $response->json();
                Cache::put($cacheKey, $data, 60); // cache the data for 1 hour
                return $data;
            } else {
                throw new Exception('Unable to fetch weather data');
            }
        }
        return json_decode($data, true);
    }
}
