<?php

namespace App\Services;

use App\Exceptions\HandicapCalculationException;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\PlayerProfile;
use App\Models\SystemSetting;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class PlayerService
{
    public function index()
    {

        $players = User::with('profile', 'player')
            ->whereIn('role', ['player', 'user'])
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->orderBy('user_profiles.last_name', 'desc')
            ->select('users.*')
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
            ->whereIn('role', ['player', 'member', 'user'])
            ->where('active', true)
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


            return [
                'player_profile_id' => $player->player->player_profile_id,
                'first_name' => $player->profile->first_name ?? '',
                'last_name' => $player->profile->last_name ?? '',
                'email' => $player->email,
                'account_no' => $player->player->account_no ?? '',
                'whs_no' => $player->player->whs_no ?? '',
                'gender' => $player->profile->sex,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Players retrieved successfully',
            'players' => $players
        ]);
    }


    public function getAvailablePlayers(Request $request)
    {
        $tournamentId = $request->query('tournament_id');

        // Get all players not already in tournament
        $query = PlayerProfile::with('user.profile')
            ->whereHas('user', function ($q) {
                $q->where('active', true);
            });

        if ($tournamentId) {
            $query->whereNotIn('player_profile_id', function ($q) use ($tournamentId) {
                $q->select('player_profile_id')
                    ->from('participants')
                    ->where('tournament_id', $tournamentId);
            });
        }

        $players = $query->get()->map(function ($player) {
            return [
                'player_profile_id' => $player->player_profile_id,
                'first_name' => $player->user->profile->first_name ?? '',
                'last_name' => $player->user->profile->last_name ?? '',
                'whs_no' => $player->whs_no ?? 'N/A',
                'account_no' => $player->account_no ?? 'N/A'
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Available players retrieved successfully',
            'players' => $players
        ]);
    }
}
