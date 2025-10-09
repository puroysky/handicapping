<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlayerService
{
    public function index()
    {

        $players = User::with('profile', 'player')
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();


        return view('admin.players.players', compact('players'));
    }
}
