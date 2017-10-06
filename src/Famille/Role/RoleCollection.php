<?php

namespace Albacode\Famille\Role;

use Doctrine\Common\Collections\ArrayCollection;

class RoleCollection extends ArrayCollection
{
    /**
     * @param RoleInterface $role
     * @return $this
     */
    public function addRole(RoleInterface $role) {
        $this->add($role);
        return $this;
    }

    /**
     * @param RoleInterface $role
     * @return $this
     */
    public function removeRole(RoleInterface $role) {
        $this->remove($role);
        return $this;
    }

}
