<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hexadog\ThemesManager\Facades\ThemesManager;

class HomeController extends Controller
{

    public function __construct() 
    {
        ThemesManager::set('codeanddeploy/front-theme1');
    }

    public function index() 
    {
        return view('welcome');
    }
}