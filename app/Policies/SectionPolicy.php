<?php

namespace App\Policies;

use App\Documentation;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Section;
use Illuminate\Auth\Access\Response;
use JotaEleSalinas\AdminlessLdap\LdapUser;

class SectionPolicy
{
    use HandlesAuthorization;

    //Hinzufügen eines Unterabschnitts zu einem existierenden Abschnitt:
    //nur, wenn das Dokument für andere Benutzer gesperrt ist; nur, wenn der Abschnitt nicht von einem Ausbilder gesperrt wurde;
    //nur, wenn der Benutzer entweder Ausbilder ist oder ihm das Dokument gehört.
    public function create(LdapUser $ldap_user, Section $section) {
        $user = app()->user;
        $documentation = $section->getUltimateParent();
        if (! $documentation instanceof Documentation) {
            $lockedBy = null;
        }
        else {
            $lockedBy = $documentation->lockedBy;
        }
        if (! $user->is($lockedBy)) {
            return Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten können.');
        }
        if ($section->is_locked) {
            return Response::deny('Dieser Abschnitt wurde von einem Ausbilder gesperrt. Er kann nicht bearbeitet werden.');
        }
        return $user->isAdmin() || $user->is($section->getUser())
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Ihre eigenen Dokumente bearbeiten.');
    }

    //Hinzufügen eines Abschnitts zur Projektdokumentation:
    //nur, wenn das Dokument für andere Benutzer gesperrt ist; nur, wenn der Benutzer entweder Ausbilder ist oder ihm das Dokument gehört.
    public function createForDoc(LdapUser $user, Section $section) {
        $user = app()->user;
        $documentation = $user->project->documentation;
        if (! $user->is($documentation->lockedBy)) {
            return Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten können.');
        }
        return $user->isAdmin() || $user->is($documentation->getUser())
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Ihre eigenen Dokumente bearbeiten.');
    }

    /*
    public function delete(LdapUser $ldap_user, Section $section) {
        $user = app()->user;
        $documentation = $section->getUltimateParent();
        if (! $documentation instanceof Documentation) {
            $lockedBy = null;
        }
        else {
            $lockedBy = $documentation->lockedBy;
        }
        return ($user->isAdmin() || $user->is($section->getUser())) && $user->is($lockedBy)
            ? Response::allow()
            : Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten.');
    }

    public function edit(LdapUser $ldap_user, Section $section) {
        $user = app()->user;
        return $user->isAdmin() || $user->is($section->getUser());
    }
    */

    //Sperren eines Abschnitts: Nur Ausbilder, wenn das Dokument für andere Benutzer gesperrt ist.
    public function lock(LdapUser $ldap_user, Section $section) {
        $user = app()->user;
        $parent = $section->getUltimateParent();
        if (! $user->isAdmin()) {
            return Response::deny('Nur Ausbilder dürfen Abschnitte sperren.');
        }
        return $section instanceof Section && (! is_null($parent)) && $user->is($parent->lockedBy)
            ? Response::allow()
            : Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten.');
    }
}
