<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffAccountController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:150',
            ],
            'gender' => [
                'nullable',
                Rule::in([
                    'male',
                    'female',
                ]),
            ],
        ]);

        $search = trim(
            (string) ($validated['search'] ?? '')
        );

        $gender = $validated['gender'] ?? null;

        $staffAccounts = User::query()
            ->where('role', User::ROLE_STAFF)
            ->withCount([
                'createdOrders',
                'processedPayments',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $query) use ($search): void {
                            $query
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'username',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->when(
                $gender,
                fn(Builder $query): Builder => $query->where(
                    'gender',
                    $gender
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total_staff' => User::query()
                ->where('role', User::ROLE_STAFF)
                ->count(),

            'male_staff' => User::query()
                ->where('role', User::ROLE_STAFF)
                ->where('gender', 'male')
                ->count(),

            'female_staff' => User::query()
                ->where('role', User::ROLE_STAFF)
                ->where('gender', 'female')
                ->count(),

            'new_this_month' => User::query()
                ->where('role', User::ROLE_STAFF)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        return view(
            'admin.staff-accounts.index',
            compact(
                'staffAccounts',
                'summary',
                'search',
                'gender'
            )
        );
    }

    public function create(): View
    {
        $this->ensureAdmin();

        return view('admin.staff-accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        $this->preparePhone($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'min:3',
                'max:50',
                'unique:users,username',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^\+62[1-9][0-9]{7,13}$/',
                'unique:users,phone',
            ],
            'gender' => [
                'nullable',
                Rule::in([
                    'male',
                    'female',
                ]),
            ],
            'date_of_birth' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'phone.regex' => 'Nomor telepon harus menggunakan format Indonesia yang benar.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $staff = User::query()->create([
            'name' => trim($validated['name']),
            'username' => strtolower(trim($validated['username'])),
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
            'google_id' => null,
            'avatar' => null,
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'role' => User::ROLE_STAFF,
            'email_verified_at' => now(),
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route(
                'management.staff-accounts.show',
                $staff
            )
            ->with(
                'success',
                'Akun staff berhasil dibuat.'
            );
    }

    public function show(User $user): View
    {
        $this->ensureAdmin();
        $this->ensureStaff($user);

        $user->loadCount([
            'createdOrders',
            'processedPayments',
        ]);

        $recentOrders = $user->createdOrders()
            ->latest('ordered_at')
            ->limit(10)
            ->get();

        $recentPayments = $user->processedPayments()
            ->with([
                'order:id,order_number,customer_name',
            ])
            ->latest()
            ->limit(10)
            ->get();

        $statistics = [
            'created_orders' => $user->createdOrders()->count(),

            'processed_payments' => $user
                ->processedPayments()
                ->count(),

            'payment_total' => $user
                ->processedPayments()
                ->where('status', 'paid')
                ->sum('amount'),

            'orders_today' => $user
                ->createdOrders()
                ->whereDate('ordered_at', today())
                ->count(),
        ];

        return view(
            'admin.staff-accounts.show',
            compact(
                'user',
                'recentOrders',
                'recentPayments',
                'statistics'
            )
        );
    }

    public function edit(User $user): View
    {
        $this->ensureAdmin();
        $this->ensureStaff($user);

        return view(
            'admin.staff-accounts.edit',
            compact('user')
        );
    }

    public function update(
        Request $request,
        User $user
    ): RedirectResponse {
        $this->ensureAdmin();
        $this->ensureStaff($user);
        $this->preparePhone($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'min:3',
                'max:50',
                Rule::unique('users', 'username')
                    ->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^\+62[1-9][0-9]{7,13}$/',
                Rule::unique('users', 'phone')
                    ->ignore($user->id),
            ],
            'gender' => [
                'nullable',
                Rule::in([
                    'male',
                    'female',
                ]),
            ],
            'date_of_birth' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'phone.regex' => 'Nomor telepon harus menggunakan format Indonesia yang benar.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $data = [
            'name' => trim($validated['name']),
            'username' => strtolower(
                trim($validated['username'])
            ),
            'email' => strtolower(
                trim($validated['email'])
            ),
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'role' => User::ROLE_STAFF,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()
            ->route(
                'management.staff-accounts.show',
                $user
            )
            ->with(
                'success',
                'Akun staff berhasil diperbarui.'
            );
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureAdmin();
        $this->ensureStaff($user);

        DB::transaction(function () use ($user): void {
            /*
             * Relasi created_by dan processed_by menggunakan nullOnDelete,
             * sehingga riwayat pesanan dan pembayaran tetap tersimpan.
             */
            $user->delete();
        });

        return redirect()
            ->route('management.staff-accounts.index')
            ->with(
                'success',
                'Akun staff berhasil dihapus.'
            );
    }

    private function ensureAdmin(): void
    {
        abort_unless(
            auth()->check()
                && auth()->user()->isAdmin(),
            403,
            'Hanya admin yang dapat mengelola akun staff.'
        );
    }

    private function ensureStaff(User $user): void
    {
        abort_unless(
            $user->isStaff(),
            404
        );
    }

    private function preparePhone(Request $request): void
    {
        $phone = trim(
            (string) $request->input('phone')
        );

        if ($phone === '') {
            $request->merge([
                'phone' => null,
            ]);

            return;
        }

        $digits = preg_replace(
            '/[^0-9]/',
            '',
            $phone
        );

        if (!$digits) {
            $request->merge([
                'phone' => null,
            ]);

            return;
        }

        if (str_starts_with($digits, '62')) {
            $digits = substr($digits, 2);
        }

        $digits = ltrim($digits, '0');

        $request->merge([
            'phone' => '+62' . $digits,
        ]);
    }
}
