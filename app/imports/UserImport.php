<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $rules = [
            'role' => 'required|in:admin,siswa,guru,kepsek,caraka',
            'name' => 'required|string|min:3|unique:users,name',
        ];

        if ($row['role'] === 'admin') {
            $rules['email'] = 'required|email|unique:users,email';
        } elseif ($row['role'] === 'kepsek') {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        } elseif ($row['role'] === 'siswa') {
            $rules['kelas'] = 'required|string';
        }

        $validator = Validator::make($row, $rules);
        if ($validator->fails()) {
            throw new \Exception('Validation failed for row: ' . implode(', ', $validator->errors()->all()));
        }

        $data = [
            'role' => $row['role'],
            'name' => $row['name'],
        ];

        if (in_array($row['role'], ['siswa', 'guru', 'caraka'])) {
            if ($row['role'] !== 'caraka') {
                $usernameLength = $row['role'] === 'siswa' ? 10 : 18;
                do {
                    $generatedUsername = fake()->numerify(str_repeat('#', $usernameLength));
                } while (User::where('username', $generatedUsername)->exists());
                $data['username'] = $generatedUsername;
            } else {
                $data['username'] = null;
            }

            $lastUser = User::where('email', 'like', 'akun%@sarpras.com')
                ->orderBy('email', 'desc')
                ->first();
            $nextNumber = $lastUser ? intval(preg_replace('/[^0-9]/', '', $lastUser->email)) + 1 : 1;
            $data['email'] = "akun" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT) . "@sarpras.com";
        } else {
            $data['email'] = $row['email'];
        }

        if ($row['role'] === 'admin') {
            $data['password'] = bcrypt('petugas123');
        } elseif ($row['role'] === 'kepsek') {
            $data['password'] = bcrypt($row['password']);
        } else {
            $data['password'] = bcrypt('SarprasTB123');
        }

        if ($row['role'] === 'siswa') {
            $data['kelas'] = $row['kelas'];
        }

        return new User($data);
    }
}
