<?php

namespace App\Repositories\Role;

class RoleRepository
{
    public function __construct(Role $role)
    {
        $this->model = $role;
    }
}
