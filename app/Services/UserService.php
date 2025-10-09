<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{


    public function index()
    {

        $users = User::with('profile', 'player')
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();


        return view('admin.users.users', compact('users'));
    }


    public function create()
    {
        return view('admin.users.create-user-form');
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {

            $user = new \App\Models\User();


            $user->email = $request['email'];
            $user->role = $request['role'];
            $user->password = bcrypt(\Illuminate\Support\Str::random(8));
            $user->active = $request['active'] ?? true;
            $user->created_by = Auth::id();
            $user->save();



            $this->storeProfile($user, $request);
            $this->storePlayer($user, $request);

            DB::commit();

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
                'redirect' => route('admin.users.index')
            ], 201);
        } catch (Exception $e) {

            DB::rollback();
            return response()->json([
                'message' => 'Error creating user',
            ], 500);
        }
    }

    private function storeProfile(User $user, $request)
    {
        return $user->profile()->create([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'] ?? null,
            'last_name' => $request['last_name'],
            'birthdate' => $request['birth_date'],
            'sex' => $request['sex'] ?? null,
            'user_desc' => $request['user_desc'] ?? null,
            'remarks' => $request['remarks'] ?? null,
            'phone' => $request['phone'] ?? null,
            'address' => $request['address'] ?? null,
            'avatar' => null,
            'created_by' => Auth::id(),
        ]);
    }

    private function storePlayer(User $user, $request)
    {
        if ($request['role'] !== 'user') {
            return null;
        }
        return $user->player()->create([
            'user_profile_id' => $user->profile->user_profile_id,
            'account_no' => $request['account_no'],
            'whs_no' => $request['whs_no'],
            'created_by' => Auth::id(),
        ]);
    }
}
