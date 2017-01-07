<?php

namespace service\Dependency;

class UserRoleManager
{

    protected $sc;
    protected $roles;
    static $userData = null;

    public function set(\Slim\Container $container)
    {
        $this->sc = $container;
        $this->roles = $this->sc['user_roles'];
        $container['userRoleManager'] = $this;
    }

    public static function setUserData($data)
    {
        static::$userData = (array)$data;
    }

    public function isAllowed($routeName)
    {

        if (!static::$userData || empty(static::$userData['user_role_name'])) {
            throw new \Exception('Missing user role or user data is not set in UserRoleManager');
            return false;
        }

        $userAllowedRoles = $this->getUserRoleLevels();

        foreach ($this->sc->slim_magic_settings['routes'] as $route => $spec) {

            if (!empty($spec['name']) && $spec['name'] == $routeName) {

                if (!empty($spec['arguments']['roleAllowed'])) {

                    if (in_array($spec['arguments']['roleAllowed'], $userAllowedRoles)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function getUserRoleLevels()
    {
        $currentUserRole = $this->getUserRoleName();
        foreach ($this->roles as $role => $inheritted) {

            if ($currentUserRole == $role || in_array($currentUserRole, $inheritted)) {
                return array_merge($inheritted, [$role]);
            }
        }

        return [];
    }

    public function getUserRoleName()
    {
        return static::$userData['user_role_name'];
    }

}
