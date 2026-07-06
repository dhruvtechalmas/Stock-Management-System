<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller // implements HasMiddleware
{
    /*
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user.index', only: ['index']),
            new Middleware('permission:user.create', only: ['create', 'store']),
            new Middleware('permission:user.edit', only: ['edit', 'update']),
            new Middleware('permission:user.delete', only: ['destroy']),
            new Middleware('permission:user.view', only: ['show']),
        ];
    }
    */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')
            ->latest()
            ->paginate(25);

           $roles = Role::where('name', 'Kitchen Staff')->get();

        return view('stocks.users.list', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User();

         $roles = Role::where('name', 'Kitchen Staff')->get();

        return view('stocks.users.create', compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        // if ($request->hasFile('profile_image')) {

        //     $data['profile_image'] = $request
        //         ->file('profile_image')
        //         ->store('users', 'public');
        // }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

       $user->assignRole('Kitchen Staff');

        return redirect()->route('users.index')->with([
            'message' => 'User created successfully!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('stocks.users.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('stocks.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        //  if ($request->hasFile('profile_image')) {

        //     if ($user->profile_image) {
        //         Storage::disk('public')->delete($user->profile_image);
        //     }

        //     $data['profile_image'] = $request
        //         ->file('profile_image')
        //         ->store('users', 'public');
        // }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with([
            'message' => 'User updated successfully!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        return redirect()->route('users.index')->with([
            'message' => 'User deleted successfully!',
            'alert-type' => 'success'
        ]);
    }
}