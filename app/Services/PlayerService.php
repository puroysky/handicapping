<?php

namespace App\Services;

use App\Exceptions\HandicapCalculationException;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\PlayerProfile;
use App\Models\Score;
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


    protected $bracket = [];
    protected $maxScorePerUser;
    protected $minScoresPerUser;

    protected $handicapConfig;
    protected PlayerProfile $playerProfile;


    public function index()
    {



        return view('admin.players.players');
    }

    public function datatable($request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search');
        $searchValue = is_array($search) ? ($search['value'] ?? '') : '';

        // Base query
        $query = User::with('profile', 'player', 'scores')
            ->whereIn('role', ['player', 'user'])
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id');

        // Search
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('user_profiles.first_name', 'like', "%{$searchValue}%")
                    ->orWhere('user_profiles.last_name', 'like', "%{$searchValue}%")
                    ->orWhere('users.email', 'like', "%{$searchValue}%")
                    ->orWhere('users.email', 'like', "%{$searchValue}%");
            });
        }

        // Count
        $recordsTotal =  User::with('profile', 'player')
            ->whereIn('role', ['player', 'user'])->count();
        $recordsFiltered = $query->count();

        // Sorting - apply proper ordering
        $query->orderBy('users.created_at', 'desc');

        // Pagination
        $data = $query->offset($start)->limit($length)->get();

        // Format data
        $formulas = $data->map(function ($formula) {
            return [
                'player_profile_id' => $formula->player->player_profile_id ?? null,
                'first_name' => $formula->profile->first_name ?? '',
                'last_name' => $formula->profile->last_name ?? '',
                'middle_name' => $formula->profile->middle_name ?? '',
                'whs_no' => $formula->player->whs_no ?? 'N/A',
                'account_no' => $formula->player->account_no ?? 'N/A',
                'email' => $formula->email,
                'birthdate' => $formula->profile->birthdate ? Carbon::parse($formula->profile->birthdate)->format('M d, Y') : '-',
                'sex' => $formula->profile->sex ?? 'N/A',
                'status' => $formula->active,
                'no_of_scores' => $formula->scores->count(),
                'active' => $formula->active,
                'created_at' => $formula->created_at->format('M d, Y'),
                'actions' => null,
                'avatar' => $formula->profile->avatar ?? null
            ];
        });

        return response()->json([
            'draw' => (int)$draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $formulas
        ]); // Base query

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



    public function getHandicap($playerid)
    {

        $this->playerProfile = PlayerProfile::with('user')->where('player_profile_id', $playerid)
            ->first();


        $config = $this->loadBracketConfiguration();


        $scores = Score::select('score_id', 'user_id', 'score_differential', 'holes_played', 'date_played', 'adjusted_gross_score', 'scores.course_id', 'tees.tee_id', 'slope_rating', 'course_rating', 'courses.course_name', 'tees.tee_name')
            ->leftJoin('courses', 'scores.course_id', '=', 'courses.course_id')
            ->leftJoin('tees', 'scores.tee_id', '=', 'tees.tee_id')
            ->whereBetween('date_played', [$config['score_date']['start'], $config['score_date']['end']])
            ->where('user_id', $this->playerProfile->user_id)
            ->limit($this->maxScorePerUser * 2)
            ->orderBy('date_played', 'desc')->get();



        $handicapService = new LocalHandicapIndexService();
        $scores = $handicapService->calculate($scores, $config);


        return array_merge($scores, [
            'profile' => [
                'name' => $this->playerProfile->userProfile->first_name . ' ' . $this->playerProfile->userProfile->last_name,
                'whs_no' => $this->playerProfile->whs_no,
                'account_no' => $this->playerProfile->account_no,
            ]
        ]);
    }

    private function loadBracketConfiguration(): array
    {


        $handicapIndexCalTable = SystemSettingService::get('local_handicap.calculation_table');



        Log::debug('Loaded handicap index calculation table', [
            'table' => $handicapIndexCalTable
        ]);

        if (empty($handicapIndexCalTable)) {
            throw new HandicapCalculationException(
                'Unable to calculate handicap. Please try again.',
                'Tournament handicap calculation table not found',
                ['player_profile' => $this->playerProfile->toArray()]
            );
        }

        $this->bracket = collect($handicapIndexCalTable)
            ->sortByDesc('max')
            ->values()
            ->toArray();

        $this->maxScorePerUser = max(array_column($this->bracket, 'max'));
        $this->minScoresPerUser = min(array_column($this->bracket, 'min'));

        if (empty($this->bracket)) {
            throw new HandicapCalculationException('Handicap calculation table is empty');
        }


        return array(
            'bracket' => $this->bracket,
            'min_scores_per_user' => $this->minScoresPerUser,
            'max_scores_per_user' => $this->maxScorePerUser,
            'score_date' =>  [
                'start' => SystemSettingService::get('local_handicap.calculation_start_date'),
                'end' => SystemSettingService::get('local_handicap.calculation_end_date'),
            ],
        );
    }
}
