<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

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
     * Nutzer zu Admin machen
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function promote(User $user)
    {
        $user->is_admin = true;
        $user->save();

        return redirect()->route('admin.users.index')->with(
            'status', 'Der Benutzer ' . $user->ldap_username . ' wurde erfolgreich zum Admin gemacht.'
        );
    }

    /**
     * Admin zu normalem Nutzer machen
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function demote(User $user)
    {
        $user->is_admin = false;
        $user->save();

        return redirect()->route('admin.users.index')->with(
            'status', 'Der Admin ' . $user->ldap_username . ' wurde erfolgreich zum Nutzer gemacht.'
        );
    }
}
