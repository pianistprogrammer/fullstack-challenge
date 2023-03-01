<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class UserRepositoryTest extends TestCase
{
    public function testGetWeatherData()
    {
        // Mock the HTTP response
        $responseJson = '{"current": {"temp": 15, "humidity": 75, "wind_speed": 10}}';
        Http::fake([
            '*' => Http::response($responseJson, 200),
        ]);

        // Mock the cache
        Cache::shouldReceive('get')->andReturn(null);
        Cache::shouldReceive('put')->once();

        // Call the function
        $userRepository = new UserRepository();
        $result = $userRepository->getWeatherData(51.5074, -0.1278);

        // Assert the result
        $expected = json_decode($responseJson, true);
        $this->assertEquals($expected, $result);
    }

    public function testGetWeatherDataWithCache()
    {
        // Mock the cache
        $data = '{"current": {"temp": 15, "humidity": 75, "wind_speed": 10}}';
        Cache::shouldReceive('get')->once()->andReturn($data);
        Cache::shouldNotReceive('put');

        // Call the function
        $userRepository = new UserRepository();
        $result = $userRepository->getWeatherData(51.5074, -0.1278);

        // Assert the result
        $expected = json_decode($data, true);
        $this->assertEquals($expected, $result);
    }

    public function testGetWeatherDataWithInvalidResponse()
    {
        // Mock the HTTP response
        Http::fake([
            '*' => Http::response('', 500),
        ]);

        // Mock the cache
        Cache::shouldReceive('get')->andReturn(null);
        Cache::shouldNotReceive('put');

        // Call the function and expect an exception
        $this->expectException(Exception::class);
        $userRepository = new UserRepository();
        $userRepository->getWeatherData(51.5074, -0.1278);
    }
}
