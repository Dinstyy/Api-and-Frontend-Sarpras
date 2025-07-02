<?php

namespace App\Http\Controllers;

use App\Custom\Formatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Detect role based on username length before validation
        $role = $request->input('role');
        $username = $request->input('username');
        if (!$role && $username) {
            if (strlen($username) == 10) {
                $role = 'siswa';
            } elseif (strlen($username) == 18) {
                $role = 'guru';
            }
        }

        // Set dynamic validation rules based on detected or provided role
        $rules = [
            'password' => 'required|string|min:8',
        ];

        if ($role) {
            $rules['role'] = 'string|in:admin,caraka,guru,kepsek,siswa'; // Role is optional but validated if present
        }

        if (in_array($role, ['siswa', 'guru'])) {
            $rules['username'] = 'required|string';
        } else {
            $rules['email'] = 'required|email';
        }

        // Add role to request data if detected
        if ($role) {
            $request->merge(['role' => $role]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return Formatter::apiResponse(422, "Validation failed", null, $validator->errors()->all());
            }
            return back()->withErrors($validator);
        }

        // Validate role explicitly if not already handled by validator
        if (!$role || !in_array($role, ['admin', 'kepsek', 'caraka', 'siswa', 'guru'])) {
            if ($request->wantsJson()) {
                return Formatter::apiResponse(422, "Invalid role");
            }
            return back()->withErrors(['role' => 'Role tidak valid']);
        }

        $user = User::where('role', $role)
            ->when(in_array($role, ['siswa', 'guru']), function ($query) use ($request) {
                $query->where('username', $request->input('username'));
            }, function ($query) use ($request) {
                $query->where('email', $request->input('email'));
            })
            ->first();


    if (!$user || !Hash::check($request->password, $user->password)) {
        if ($request->wantsJson()) {
            return Formatter::apiResponse(400, "Credentials don't match");
        }
        return back()->withErrors(['login' => 'Email/username atau password salah']);
    }

    // Tambahkan log activity:
    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'login',
        'description' => 'User login: ' . $user->name,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    if (!$request->wantsJson()) {
        Auth::login($user, $request->boolean('remember'));
        return redirect()->route('dashboard');
    }

    $user->tokens()->delete();
    $token = $user->createToken("auth_token")->plainTextToken;

    return Formatter::apiResponse(200, "Logged in", [
        "token" => $token,
        "user" => $user
    ]);
}

public function logout(Request $request)
{
    if ($request->wantsJson()) {
        $user = Auth::guard("sanctum")->user();
        if ($user) {
            $user->tokens()->delete();
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'logout',
                'description' => 'User logout: ' . $user->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        return Formatter::apiResponse(200, "Logged out");
    }

    $user = Auth::user();
    if ($user) {
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'logout',
            'description' => 'User logout: ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken(); 
    return redirect('/login');
}

    public function self()
    {
        $user = auth()->user();

        return Formatter::apiResponse(200, "User data retrieved", $user);
    }

    public function showLogin()
    {
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'kepsek'])) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function dashboard()
    {
        if (!in_array(auth()->user()->role, ['admin', 'kepsek'])) {
            abort(403, 'Unauthorized access');
        }
        return view('dashboard');
    }
}
