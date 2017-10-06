<?php

namespace Albacode\Famille\Membre;

use Albacode\Famille\Role\RoleCollection;
use Albacode\Famille\Role\RoleInterface;

interface MembreInterface
{

    /**
     * @return string
     */
    public function getGenre();

    /**
     * @return string
     */
    public function getNom();

    /**
     * @param RoleInterface $role
     * @return $this
     */
    public function addRole(RoleInterface $role);

    /**
     * @param RoleInterface $role
     * @return $this
     */
    public function removeRole(RoleInterface $role);

    /**
     * @return RoleCollection
     */
    public function getRoles();

    /**
     * @param $roleClass
     * @return boolean
     */
    public function hasRole($roleClass);

}
