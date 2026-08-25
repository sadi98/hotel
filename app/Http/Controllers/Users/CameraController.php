<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CameraController extends Controller
{
    public function index()
    {
        return view('users.camera.index');
    }
}
