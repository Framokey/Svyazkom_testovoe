<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        return view('admin.layouts.app', ['user' => $user]);
    }
}
