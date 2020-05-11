## Intranet mit Laravel
TODO: Readme schreiben

#### Config Testserver
````dotenv
LDAP_SCHEMA=FreeIPA
LDAP_HOSTS=192.168.170.200
LDAP_BASE_DN=cn=users,cn=compat,dc=abp,dc=test
LDAP_USER_SEARCH_ATTRIBUTE=uid
LDAP_USER_BIND_ATTRIBUTE=uid
LDAP_USER_FULL_DN_FMT=${LDAP_USER_BIND_ATTRIBUTE}=%s,${LDAP_BASE_DN}
LDAP_CONNECTION=default
AUTH_USER_KEY_FIELD=username
````