<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\MembreInterface;

class Mere implements RoleInterface
{

    /**
     * @var MembreInterface
     */
    protected $enfant;

    /**
     * Mere constructor.
     * @param MembreInterface $enfant
     */
    public function __construct(MembreInterface $enfant)
    {
        $this->enfant = $enfant;
    }
}
