<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tee;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $tees = Tee::all();
        $defaultTeeLadies = Cache::get('default_tee_ladies', null);
        return view('admin.settings.settings', compact('tees', 'defaultTeeLadies'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'default_tee_ladies' => 'required|exists:tees,id',
        ]);
        Cache::put('default_tee_ladies', $request->input('default_tee_ladies'));
        return redirect()->route('admin.settings')->with('success', 'Settings saved successfully.');
    }
}
