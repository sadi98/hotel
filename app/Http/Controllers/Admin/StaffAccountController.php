<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffAccountController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin();
        $search = trim((string) $request->query('search'));

        $staffAccounts = User::query()
            ->where('role', 'staff')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.staff-accounts.index', compact('staffAccounts', 'search'));
    }

    public function create(): View
    {
        $this->ensureAdmin();
        return view('admin.staff-accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();
        $validated = $this->validateStaff($request);
        $validated['role'] = 'staff';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('management.staff-accounts.index')
            ->with('success', 'Akun staff berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        $this->ensureAdmin();
        $this->ensureStaff($user);
        return view('admin.staff-accounts.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin();
        $this->ensureStaff($user);
        $validated = $this->validateStaff($request, $user);
        $validated['role'] = 'staff';

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('management.staff-accounts.index')
            ->with('success', 'Akun staff berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureAdmin();
        $this->ensureStaff($user);
        $user->delete();

        return back()->with('success', 'Akun staff berhasil dihapus.');
    }

    private function validateStaff(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('users', 'username')->ignore($user?->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'phone' => ['nullable', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($user?->id)],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    private function ensureStaff(User $user): void
    {
        abort_unless($user->role === 'staff', 404);
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }
}
