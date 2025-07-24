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
        DB::table('tenants')->delete();
        DB::table('rents')->delete();

        return redirect()->route('settings.index')->with('success', 'Database cleaned successfully.');
    }
}
