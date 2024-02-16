<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Chef;
use App\Models\Reservations;

class HomeController extends Controller
{
    public function imah()
    {
        $chefs = Chef::all();
        $menus = Menu::all();
        $reservations = Reservations::all();
        return view('imah', compact('chefs','menus','reservations'));
    }
}
