<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileUserController extends Controller
{
    public function index()
    {
        return view('users.profile.index');
    }
}