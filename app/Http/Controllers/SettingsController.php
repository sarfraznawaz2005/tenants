<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function download()
    {
        $dbPath = config('database.connections.sqlite.database');
        return Response::download($dbPath);
    }

    public function clean()
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::table('comments')->truncate();
        DB::table('adjustments')->truncate();
        DB::table('rents')->truncate();
        DB::table('bills')->truncate();
        DB::table('bill_types')->truncate();
        DB::table('tenants')->truncate();
        DB::table('users')->truncate();
        DB::table('cache')->truncate();
        DB::table('jobs')->truncate();

        DB::statement('PRAGMA foreign_keys = ON');

        return redirect()->route('settings.index')->with('success', 'Database cleaned successfully.');
    }
}
