<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\MembreInterface;

class Enfant implements RoleInterface
{

    /**
     * @var Membre\MembreInterface
     */
    protected $parent_1;

    /**
     * @var Membre\MembreInterface
     */
    protected $parent_2;

    public function __construct(MembreInterface $parent_1, MembreInterface $parent_2)
    {
        $this->parent_1 = $parent_1;
        $this->parent_2 = $parent_2;
    }
}
