<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Custom\Formatter;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;
use App\Exports\UsersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        // Unchanged, already perfect for API
        $userQuery = User::query();
        $validColumns = ['name', 'email', 'role'];

        if (request()->filled('search')) {
            $searchTerm = '%' . request()->input('search') . '%';
            $userQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('kelas', 'LIKE', $searchTerm)
                    ->orWhere('role', 'LIKE', $searchTerm)
                    ->orWhere('username', 'LIKE', $searchTerm);
            });
        }

        foreach (request()->except(['page', 'size', 'sortBy', 'sortDir', 'search']) as $key => $value) {
            if (in_array($key, $validColumns)) {
                $userQuery->where($key, $value);
            }
        }

        $sortBy = in_array(request()->sortBy, $validColumns) ? request()->sortBy : 'created_at';
        $sortDir = strtolower(request()->sortDir) === 'desc' ? 'DESC' : 'ASC';
        $userQuery->orderBy($sortBy, $sortDir);

        $size = min(max(request()->size ?? 8, 1), 100);
        $users = $userQuery->simplePaginate($size);

        return Formatter::apiResponse(200, 'User list retrieved', $users);
    }

    public function create()
    {
        // Unchanged, web-only
        return view('users.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'role' => 'required|string|in:admin,siswa,guru,kepsek,caraka',
            'name' => 'required|string|min:3|unique:users,name',
        ];

        if ($request->input('role') === 'admin') {
            $rules['email'] = 'required|email|unique:users,email';
        } elseif ($request->input('role') === 'kepsek' || $request->input('role') === 'caraka') {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        } elseif ($request->input('role') === 'siswa') {
            $rules['kelas'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(422, 'Validation failed', null, $validator->errors()->all());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (in_array($validated['role'], ['siswa', 'guru', 'caraka'])) {
            if ($validated['role'] !== 'caraka') {
                $usernameLength = $validated['role'] === 'siswa' ? 10 : 18;
                do {
                    $generatedUsername = fake()->numerify(str_repeat('#', $usernameLength));
                } while (User::where('username', $generatedUsername)->exists());
                $validated['username'] = $generatedUsername;
            } else {
                $validated['username'] = null;
            }

            $lastUser = User::where('email', 'like', 'akun%@sarpras.com')
                ->orderBy('email', 'desc')
                ->first();
            $nextNumber = $lastUser ? intval(preg_replace('/[^0-9]/', '', $lastUser->email)) + 1 : 1;
            $validated['email'] = "akun" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT) . "@sarpras.com";

            if ($validated['role'] !== 'caraka') {
                $validated['password'] = bcrypt('SarprasTB123');
            }
        } elseif ($validated['role'] === 'admin') {
            $validated['password'] = bcrypt('petugas123');
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        if ($request->filled('kelas')) {
            $validated['kelas'] = $request->input('kelas');
        }

        $user = User::create($validated);

        if ($request->expectsJson()) {
            return Formatter::apiResponse(201, 'User created successfully', $user);
        }
        return redirect()->route('users.viewIndex')->with('success', 'User created successfully');
    }

    public function viewIndex(Request $request)
    {
        $userQuery = User::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $userQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', $searchTerm)
                    ->orWhere('kelas', 'LIKE', $searchTerm)
                    ->orWhere('role', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('username', 'LIKE', $searchTerm);
            });
        }

        $users = $userQuery->paginate(10);

        if ($request->expectsJson()) {
            return Formatter::apiResponse(200, 'User list retrieved', $users);
        }
        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            if (request()->expectsJson()) {
                return Formatter::apiResponse(404, 'User not found');
            }
            abort(404);
        }

        if (request()->expectsJson()) {
            return Formatter::apiResponse(200, 'User found', $user);
        }
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        // Unchanged, web-only
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(404, 'User not found');
            }
            return redirect()->route('users.viewIndex')->withErrors(['error' => 'User not found']);
        }

        $rules = [
            'name' => 'sometimes|string|min:3|unique:users,name,' . $id,
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|nullable',
            'role' => 'sometimes|string|in:admin,siswa,guru,kepsek,caraka',
        ];

        if ($request->input('role', $user->role) === 'siswa') {
            $rules['kelas'] = 'required|string';
            $rules['username'] = 'sometimes|string|unique:users,username,' . $id;
        } elseif ($request->input('role', $user->role) === 'guru') {
            $rules['username'] = 'sometimes|string|unique:users,username,' . $id;
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(422, 'Validation failed', null, $validator->errors()->all());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($request->expectsJson()) {
            return Formatter::apiResponse(200, 'User updated successfully', $user);
        }
        return redirect()->route('users.viewIndex')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            if (request()->expectsJson()) {
                return Formatter::apiResponse(404, 'User not found');
            }
            return redirect()->route('users.viewIndex')->withErrors(['error' => 'User not found']);
        }

        $user->delete();

        if (request()->expectsJson()) {
            return Formatter::apiResponse(200, 'User deleted successfully');
        }
        return redirect()->route('users.viewIndex')->with('success', 'User deleted successfully');
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'users_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $filePath = 'exports/' . $fileName;
        Excel::store(new UsersExport, $filePath, 'public');

        if ($request->expectsJson()) {
            return Formatter::apiResponse(200, 'Excel file generated', ['url' => url('storage/' . $filePath)]);
        }
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $users = User::all();
        $fileName = 'users_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        $filePath = 'exports/' . $fileName;
        $pdf = Pdf::loadView('users.pdf', compact('users'));
        $pdf->save(storage_path('app/public/' . $filePath));

        if ($request->expectsJson()) {
            return Formatter::apiResponse(200, 'PDF file generated', ['url' => url('storage/' . $filePath)]);
        }
        return $pdf->download('users.pdf');
    }

    public function importUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return Formatter::apiResponse(422, 'Validation failed', null, $validator->errors()->all());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            Excel::import(new UserImport, $request->file('file'));
            DB::commit();
            if ($request->expectsJson()) {
                return Formatter::apiResponse(200, 'Users imported successfully');
            }
            return redirect()->route('users.viewIndex')->with('success', 'Users imported successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return Formatter::apiResponse(500, 'Import failed', null, $e->getMessage());
            }
            return redirect()->back()->withErrors(['import' => 'Import failed: ' . $e->getMessage()]);
        }
    }
}
