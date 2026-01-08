<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Beneficiary;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->orderBy('nama')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
            'password_confirmation' => 'required|string|min:3',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'bidang' => 'required|string|max:255',
        ]);

        User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']), // FIX: Gunakan Hash
            'role' => 'user',
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'bidang' => $validated['bidang'],
        ]);

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        // Pastikan hanya user biasa yang bisa diedit
        if ($user->role !== 'user') {
            abort(403, 'Hanya user biasa yang dapat diedit');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Pastikan hanya user biasa yang bisa diupdate
        if ($user->role !== 'user') {
            abort(403, 'Hanya user biasa yang dapat diupdate');
        }

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:3|confirmed',
            'password_confirmation' => 'nullable|string|min:3',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'bidang' => 'required|string|max:255',
        ]);

        $updateData = [
            'username' => $validated['username'],
            'nama' => $validated['nama'],
            'nip' => $validated['nip'],
            'bidang' => $validated['bidang'],
        ];

        // Update password hanya jika diisi
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']); // FIX: Gunakan Hash
        }

        $user->update($updateData);

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // Pastikan hanya user biasa yang bisa dihapus
        if ($user->role !== 'user') {
            abort(403, 'Hanya user biasa yang dapat dihapus');
        }

        // Cek apakah user sedang digunakan (punya data beneficiaries)
        $digunakan = Beneficiary::where('bidang', $user->bidang)->exists();
        
        if ($digunakan) {
            return redirect()->route('users.index')
                             ->with('error', 'User tidak dapat dihapus karena sudah memiliki data bantuan!');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Temporary method untuk memperbaiki password user yang sudah ada
     * HAPUS METHOD INI SETELAH DIGUNAKAN!
     */
    public function fixExistingPasswords()
    {
        // Hanya untuk development, hapus di production
        if (!app()->environment('local')) {
            abort(403, 'Method hanya tersedia di environment local');
        }

        $users = User::all();
        $fixedCount = 0;

        foreach ($users as $user) {
            // Jika password tidak di-hash (kurang dari 50 karakter), reset dengan hash
            if (strlen($user->password) < 50) {
                $plainPassword = $user->password; // Simpan password asli
                $user->password = Hash::make($plainPassword);
                $user->save();
                $fixedCount++;
                
                echo "Password untuk {$user->username} ({$user->role}) di-hash dari: <strong>{$plainPassword}</strong><br>";
            }
        }

        if ($fixedCount === 0) {
            echo "Semua password sudah dalam keadaan ter-hash!";
        } else {
            echo "<br><strong>Total {$fixedCount} user telah diperbaiki!</strong><br>";
            echo "<a href='" . route('login') . "'>Kembali ke Login</a>";
        }
    }
}