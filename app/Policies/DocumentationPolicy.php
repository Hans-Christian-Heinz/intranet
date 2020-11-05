<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Documentation;
use Illuminate\Auth\Access\Response;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class DocumentationPolicy
{
    use HandlesAuthorization;

    //Auszubildende dürfen nur ihre eigenen Dokumentationen ansehen
    public function index(LdapUser $ldapUser, Documentation $documentation) {
        $user = app()->user;
        /*return ($user->is($documentation->project->user) || $user->is_admin)
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Ihre eigenen Dokumente ansehen.');*/
        if ($user->is($documentation->project->user) || $user->is_admin) {
            return true;
        }
        else {
            abort(403, 'Sofern Sie kein Ausbilder sind, dürfen Sie nur Ihre eigenen Dokumente ansehen.');
        }
    }

    //Eine Dokumentation darf nur bearbeitet werden, wenn sie für andere Benutzer gesperrt ist.
    public function store(LdapUser $ldapUser, Documentation $documentation)
    {
        $user = app()->user;
        if (! $user->is($documentation->lockedBy)) {
            return Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten können.');
        }
        return $user->is($documentation->project->user) || $user->is_admin
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Ihre eigenen Dokumente bearbeiten.');
    }

    //Auszubildende dürfen nur ihre eigene Dokumentation für andere Benutzer sperren oder freigeben
    public function lock(LdapUser $ldapUser, Documentation $documentation)
    {
        $user = app()->user;
        return $user->is($documentation->project->user) || $user->is_admin;
    }

    //Auszubildende dürfen nur für ihre eigene Dokumentation PDF-Dokumente generieren lassen.
    public function pdf(LdapUser $ldapUser, Documentation $documentation)
    {
        $user = app()->user;
        return $user->is($documentation->project->user) || $user->is_admin;
    }
}
