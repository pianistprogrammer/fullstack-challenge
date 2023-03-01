<?php
namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->all();

        return response()->json($users);
    }

    public function show($id)
    {
        $user = $this->userRepository->find($id);

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $user = $this->userRepository->create($data);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $user = $this->userRepository->update($data, $id);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $this->userRepository->delete($id);

        return response()->json(null, 204);
    }

     public function getWeather($latitude, $longitude)
    {
        $weather = $this->userRepository->getWeatherData($latitude, $longitude);

        return response()->json($weather);
    }
}
