<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function UserDashboard()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);
        return view('index', compact('userData'));
    }

    public function UserProfileStore(Request $request)
    {
        return $this->userRepository->UserProfileStore($request);
    }
    public function Userlogout(Request $request)
    {
        return $this->userRepository->UserLogout($request);
    }
    public function UserChangePassword(Request $request)
    {
        return $this->userRepository->UserChangePassword($request);
    }
}
