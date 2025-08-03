<?php

namespace App\Http\Controllers\User;

use App\Models\Canteen;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
{
    $canteens = Canteen::all();
    return view('user.dashboard', compact('canteens'));
}
    public function pilihKantin($id)
    {
        $canteen = Canteen::findOrFail($id);
        session(['selected_canteen_id' => $canteen->id]);

        return redirect()->route('user.orders.index')->with('success', 'Kantin ' . $canteen->name . ' dipilih!');
    }
}
