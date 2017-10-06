<?php

namespace Albacode\Famille\Membre;

use Albacode\Famille\Role\RoleCollection;
use Albacode\Famille\Role\RoleInterface;

abstract class AbstractMembre implements MembreInterface
{

    /**
     * @var string
     */
    protected $nom;

    /**
     * @var RoleCollection
     */
    protected $roles;

    public function __construct($nom)
    {
        $this->nom = $nom;
        $this->roles = new RoleCollection();
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return $this
     */
    protected function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addRole(RoleInterface $role)
    {
        $this->roles->addRole($role);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeRole(RoleInterface $role)
    {
        $this->roles->removeRole($role);
        return $this;
    }

    /**
     * @return RoleCollection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $roleClass
     * @return boolean
     */
    public function hasRole($roleClass)
    {
        return array_reduce(
            $this->roles->toArray(),
            function ($hasRole, RoleInterface $role) use ($roleClass) {
                return $hasRole || (get_class($role) == $roleClass);
            }
        );
    }
}
