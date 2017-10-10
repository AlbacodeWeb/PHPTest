<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\MembreInterface;

class Enfant implements RoleInterface
{

    /**
     * @var MembreInterface
     */
    protected $parent1;

    /**
     * @var MembreInterface
     */
    protected $parent2;

    public function __construct(MembreInterface $parent1, MembreInterface $parent2)
    {
        $this->parent1 = $parent1;
        $this->parent2 = $parent2;
    }
}
