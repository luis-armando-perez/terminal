<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::transaction(function () use ($request, &$userId) {

            $tenantId = DB::table('tenants')->insertGetId([
                'name' => 'Tenant-' . Str::slug($request->name) . '-' . Str::random(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //crear usuario y asociarlo al tenant
            $userId = DB::table('users')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenantId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Auth::loginUsingId($userId);

        return redirect('/inicio');
    }
}
