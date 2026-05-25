<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    //
   public function index(): View
{
    $search = request('search');

            $users = User::with('roles')
                ->when($search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->latest('created_at')
                ->paginate(10)
                ->withQueryString();

            return view('users.index', [
                'users' => $users,
            ]);
        }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

            return view('users.create', [
                'roles' => $roles,
            ]);
    }

    public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', 'min:8'],
          'role' => ['required', 'exists:roles,name'],
            'status' => ['required', 'in:active,inactive'],
    ]);

    $user =User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'status' => $validated['status'],
    ]);
    $user->assignRole($validated['role']);

    return redirect()
        ->route('users.index')
        ->with('success', 'User created successfully.');
}

public function edit(User $user): View
{
    $roles = Role::orderBy('name')->get();

    return view('users.edit', [
        'user' => $user,
        'roles' => $roles,
    ]);
}

public function update(Request $request, User $user): RedirectResponse
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email,' . $user->id,
        ],
        'role' => ['required', 'exists:roles,name'],
        'status' => ['required', 'in:active,inactive'],
    ]);

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'status' => $validated['status'],
    ]);

    if (
    $user->hasRole('super-admin') &&
    $validated['role'] !== 'super-admin'
) {
    $superAdminCount = User::role('super-admin')->count('*');

    if ($superAdminCount <= 1) {
        return back()->withErrors([
            'role' => 'At least one super admin must remain in the system.',
        ]);
    }
}

    $user->syncRoles([$validated['role']]);

    return redirect()
        ->route('users.index')
        ->with('success', 'User updated successfully.');
}

}
