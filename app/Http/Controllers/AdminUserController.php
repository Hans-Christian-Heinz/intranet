<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        if ($id == 0) {
            $users = User::orderBy('ldap_username')->paginate(30);
        }
        else {
            $users = User::where('id', $id)->paginate(30);
        }
        //$users = User::orderBy('ldap_username')->get();
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

    public function search(string $text = '') {
        $text = htmlspecialchars($text);
        //Ich weiß nicht, warum die auskommentierte Abfrage einen Fehler auslöst.
        return DB::table('users')->select('id', 'full_name')
            ->where('full_name', 'like', '%' . $text . '%')
            ->orWhere('ldap_username', 'like', '%' . $text . '%')
            ->orderBy('ldap_username')
            ->get()
            ->all();

        /*return User::where('full_name', 'like', '%' . $text . '%')
            ->orWhere('ldap_username', 'like', '%' . $text . '%')
            ->orderBy('ldap_username')
            ->get()
            ->all();*/

        //return $users;
    }
}
