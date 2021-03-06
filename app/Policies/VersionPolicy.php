<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Version;
use JotaEleSalinas\AdminlessLdap\LdapUser;
use Illuminate\Auth\Access\Response;

class VersionPolicy
{
    use HandlesAuthorization;

    //Eine alte Vedrsion eines Dokuments darf nur verwendet werden, wenn das Dokument für andere Benutzer gesperrt ist.
    //Eine alte Vedrsion eines Dokuments darf nicht verwendet werden, wenn ein Abschnitt des Dokuments von einem Ausbilder gesperrt wurde.
    public function use(LdapUser $user, Version $version)
    {
        $user = app()->user;
        $document = $version->getDocument();
        if ($document->vc_locked) {
            return Response::deny('Da mindestens 1 Abschnitt des Dokuments von einem Ausbilder gesperrt wurde, können alte Versionen nicht mehr übernommen werden,'
                . ', da bei der Übernahme sonst der Abschnitt geändert werden könnte.');
        }
        if (!$user->is($document->lockedBy)) {
            return Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie es bearbeiten können.');
        }
        return $user->is($document->project->user) || $user->is_admin
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Versionen von Dokumenten bearbeiten, die Ihnen gehören.');
    }

    //Der Veränderungsverlauf eines Dokuments darf nur bearbeitet werden, wenn das Dokument für andere Benutzer gesperrt ist.
    //Die neueste Version eines Dokuments darf nicht gelöscht werden, wenn ein Abschnitt des Dokuments von einem Ausbilder gesperrt wurde.
    public function delete(LdapUser $user, Version $version)
    {
        $user = app()->user;
        $document = $version->getDocument();

        if ($document->vc_locked && $version->is($document->latestVersion())) {
            return Response::deny('Da mindestens 1 Abschnitt des Dokuments von einem Ausbilder gesperrt wurde, kann die neuste Version nicht gelöscht werden,'
                . ', da bei der Übernahme eines alten Abschnitts sonst der Abschnitt geändert werden könnte.');
        }
        if (!$user->is($document->lockedBy)) {
            return Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie seinen Änderungsverlauf bearbeiten können.');
        }
        return $user->is($document->project->user) || $user->is_admin
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Versionen von Dokumenten löschen, die Ihnen gehören.');
    }

    //Auszubildende dürfen nur den Veränderungsverlauf ihrer eigenen Dokumente löschen.
    //Der Veränderungsverlauf eines Dokuments darf nur gelöscht werden, wenn das Dokument für andere Benutzer gesperrt ist.
    public function clearHistory(LdapUser $user, Version $version)
    {
        $user = app()->user;
        $document = $version->getDocument();

        if (!$user->is($document->lockedBy)) {
            return Response::deny('Sie müssen das Dokument für andere Benutzer sperren, bevor Sie seinen Änderungsverlauf bearbeiten können.');
        }
        return $user->is($document->project->user) || $user->is_admin
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur Versionen von Dokumenten löschen, die Ihnen gehören.');
    }

    //Auszubildende dürfen nur Versionen ihnen gehörender Dokumente ansehen
    public function vergleich(LdapUser $user, Version $version)
    {
        $user = app()->user;
        $document = $version->getDocument();
        return (!is_null($document)) && ($user->is($document->project->user) || $user->is_admin)
            ? Response::allow()
            : Response::deny('Sofern Sie kein Ausbilder sind, dürfen Sie nur den Verlauf von Dokumenten ansehen, die Ihnen gehören.');
    }
}
