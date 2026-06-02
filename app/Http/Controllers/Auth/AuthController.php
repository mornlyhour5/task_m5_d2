<?php

namespace App\Http\Controllers\Auth;

use App\DTO\CreateUserDTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\userService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Auth;

// use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected userService $userService)
    {
        $this->userService = $userService;
    }
    public function dashboard(){
        return view('dashboard');
    }

    public function login(Request $request)
    {

        $request->validate([
        'name' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('name', $request->name)->first();

    if ($user && Hash::check($request->password, $user->password)) {

            $userid = $user->id;
            $username = $user->name;
            session(['id' => $userid, 'name' => $username]);

        return redirect()->route('dashboard');
    }

    return back()->with('error', 'Invalid credentials');

    }

    public function register(Request $request)
    {
        // $name = $request->input('name');
        // $password = $request->input('password');
        // $email = $request->input('email');

        // $user = new User();
        // $user->name = $name;
        // $user->password = $password;
        // $user->email = $email;
        // $user->save();

        // return redirect()->route('login');

        $request->validate([
        'name' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6'
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);

    return redirect('/')->with('success', 'Register success');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }

    public function store(Request $request)
    {
        $dto = CreateUserDTO::formRequest($request);
        $this->userService->create($dto);
        return redirect('/')->with('success', 'Register success');
    }
}
