<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ProfileAdminController extends Controller
{
    /**
     * Menampilkan halaman profile.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return view('admin.profile.index', compact('user'));
    }

    /**
     * Memperbarui data profile tanpa foto.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        /*
     * Normalisasi nomor telepon sebelum divalidasi.
     *
     * 081234567890   menjadi +6281234567890
     * 6281234567890  menjadi +6281234567890
     * +6281234567890 menjadi +6281234567890
     * 81234567890    menjadi +6281234567890
     */
        $originalPhone = trim((string) $request->input('phone', ''));

        if ($originalPhone === '') {
            $normalizedPhone = null;
        } else {
            // Hanya ambil angka.
            $phoneDigits = preg_replace('/[^0-9]/', '', $originalPhone);

            // Hapus kode negara 62 jika sudah dimasukkan.
            if (str_starts_with($phoneDigits, '62')) {
                $phoneDigits = substr($phoneDigits, 2);
            }

            // Hapus semua angka 0 yang berada di awal.
            $phoneDigits = ltrim($phoneDigits, '0');

            // Jika hasilnya kosong, biarkan menjadi +62 agar gagal validasi.
            $normalizedPhone = '+62' . $phoneDigits;
        }

        $request->merge([
            'phone' => $normalizedPhone,
        ]);

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'username' => [
                    'required',
                    'string',
                    'max:100',
                    'alpha_dash',
                    Rule::unique('users', 'username')->ignore($user->id),
                ],

                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],

                'phone' => [
                    'nullable',
                    'string',
                    'max:16',
                    'regex:/^\+62[1-9][0-9]{7,12}$/',
                    Rule::unique('users', 'phone')->ignore($user->id),
                ],

                'gender' => [
                    'nullable',
                    Rule::in(['male', 'female']),
                ],

                'date_of_birth' => [
                    'nullable',
                    'date',
                    'before_or_equal:today',
                ],
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.string' => 'Nama lengkap harus berupa teks.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',

                'username.required' => 'Username wajib diisi.',
                'username.string' => 'Username harus berupa teks.',
                'username.max' => 'Username maksimal 100 karakter.',
                'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
                'username.unique' => 'Username sudah digunakan oleh pengguna lain.',

                'email.email' => 'Format alamat email tidak valid.',
                'email.max' => 'Alamat email maksimal 255 karakter.',
                'email.unique' => 'Alamat email sudah digunakan oleh pengguna lain.',

                'phone.string' => 'Nomor telepon harus berupa teks.',
                'phone.max' => 'Nomor telepon terlalu panjang.',
                'phone.regex' => 'Nomor telepon harus terdiri dari 8 sampai 13 angka setelah +62 dan tidak boleh diawali angka 0.',
                'phone.unique' => 'Nomor telepon sudah digunakan oleh pengguna lain.',

                'gender.in' => 'Pilihan jenis kelamin tidak valid.',

                'date_of_birth.date' => 'Tanggal lahir tidak valid.',
                'date_of_birth.before_or_equal' => 'Tanggal lahir tidak boleh melebihi tanggal hari ini.',
            ]
        );

        try {
            $user->update($validated);

            return redirect()
                ->route('management.profile')
                ->with('success', 'Profile berhasil diperbarui.');
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Profile gagal diperbarui. Silakan coba kembali.'
                );
        }
    }

    /**
     * Memperbarui foto profile secara terpisah.
     */
    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate(
            [
                'avatar' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
            ],
            [
                'avatar.required' => 'Silakan pilih foto profile.',
                'avatar.image' => 'File yang dipilih harus berupa gambar.',
                'avatar.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
                'avatar.max' => 'Ukuran foto maksimal 5 MB.',
            ]
        );

        $newAvatarPath = null;

        try {
            $avatar = $validated['avatar'];

            $fileName = Str::uuid()->toString()
                . '.'
                . $avatar->getClientOriginalExtension();

            $newAvatarPath = $avatar->storeAs(
                'admin/profile',
                $fileName,
                'public'
            );

            $oldAvatarPath = $user->avatar;

            $user->update([
                'avatar' => $newAvatarPath,
            ]);

            /*
             * Hapus foto lama jika foto tersebut merupakan file lokal.
             * URL Google atau URL eksternal tidak akan ikut dihapus.
             */
            if (
                $oldAvatarPath &&
                !Str::startsWith($oldAvatarPath, ['http://', 'https://']) &&
                Storage::disk('public')->exists($oldAvatarPath)
            ) {
                Storage::disk('public')->delete($oldAvatarPath);
            }

            return redirect()
                ->route('management.profile')
                ->with('success', 'Foto profile berhasil diperbarui.');
        } catch (Throwable $exception) {
            report($exception);

            /*
             * Jika file baru sudah tersimpan tetapi database gagal diperbarui,
             * hapus kembali file tersebut.
             */
            if (
                $newAvatarPath &&
                Storage::disk('public')->exists($newAvatarPath)
            ) {
                Storage::disk('public')->delete($newAvatarPath);
            }

            return redirect()
                ->back()
                ->with('error', 'Foto profile gagal diperbarui. Silakan coba kembali.');
        }
    }

    /**
     * Memperbarui password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validateWithBag(
            'passwordUpdate',
            [
                'current_password' => [
                    'required',
                    'current_password',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'different:current_password',
                ],

                'password_confirmation' => [
                    'required',
                    'string',
                ],
            ],
            [
                'current_password.required' => 'Password lama wajib diisi.',
                'current_password.current_password' => 'Password lama yang Anda masukkan tidak sesuai.',

                'password.required' => 'Password baru wajib diisi.',
                'password.string' => 'Password baru harus berupa teks.',
                'password.min' => 'Password baru minimal terdiri dari 8 karakter.',
                'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
                'password.different' => 'Password baru tidak boleh sama dengan password lama.',

                'password_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
                'password_confirmation.string' => 'Konfirmasi password harus berupa teks.',
            ]
        );

        try {
            $user->forceFill([
                'password' => Hash::make($validated['password']),
            ])->save();

            return redirect()
                ->route('management.profile')
                ->with('success', 'Password berhasil diperbarui.');
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->back()
                ->with('error', 'Password gagal diperbarui. Silakan coba kembali.');
        }
    }
}
