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

    public function create()
    {
        return view('admin.users.create-user-form');
    }

    /**
     * Search for players by name or email
     */
    public function searchPlayers($searchTerm)
    {
        $players = User::with('player', 'profile')
            ->where('role', 'user')
            ->where(function ($query) use ($searchTerm) {
                $query->where('email', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('profile', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('last_name', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhereHas('player', function ($q) use ($searchTerm) {
                        $q->where('account_no', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('whs_no', 'LIKE', "%{$searchTerm}%");
                    });
            })
            ->limit(10) // Limit results for performance
            ->get();







        $players = $players->map(function ($player) {
            // Convert database gender format to single character
            $gender = 'M'; // Default to Male
            if ($player->profile && $player->profile->sex) {
                $gender = $player->profile->sex === 'FEMALE' ? 'F' : 'M';
            }

            return [
                'id' => $player->id,
                'first_name' => $player->profile->first_name ?? '',
                'last_name' => $player->profile->last_name ?? '',
                'email' => $player->email,
                'account_no' => $player->player->account_no ?? '',
                'whs_no' => $player->player->whs_no ?? '',
                'gender' => $gender,
            ];
        });

        return $players;
    }
}
