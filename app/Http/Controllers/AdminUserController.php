<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('ldap_username')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(User $user) {
        if (Gate::allows('delete-user', $user)) {
            $user->delete();
            return redirect()->back()->with('status', 'Das Benutzerprofil wurde erfolgreich gelöscht.');
        }
    }
}
