<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;

class CacheController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/cache/Index');
    }

    public function clear(Request $request)
    {
        $validCommands = [
            'cache:clear',
            'route:clear',
            'config:clear',
            'view:clear',
            'optimize:clear',
            'event:clear'
        ];

        $request->validate([
            'command' => 'required|string|in:' . implode(',', $validCommands)
        ]);

        Artisan::call($request->command);

        return back()->with('success', 'Önbellek başarıyla temizlendi.');
    }
}
