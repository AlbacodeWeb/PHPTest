<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\MembreCollection;
use Albacode\Famille\Membre\MembreInterface;

class Enfant implements RoleInterface
{

    /**
     * @var MembreCollection
     */
    protected $parents;

    public function __construct(Femme $mere, MembreInterface $parent)
    {
        $this->parents = ( new MembreCollection() )
            ->addMembre($mere)
            ->addMembre($parent);
    }

    /**
     * @return $this|MembreCollection
     */

    public function getParents()
    {
        return $this->parents;
    }
}
