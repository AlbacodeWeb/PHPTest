<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\AbstractMembre;
use Albacode\Famille\Membre\MembreInterface;

abstract class AbstractConjoint implements RoleInterface
{

    /**
     * @var abstractMembre
     */
    protected $conjoint;

    /**
     * @param AbstractMembre $conjoint
     */
    public function __construct(MembreInterface $conjoint)
    {
        $this->conjoint = $conjoint;
    }
}
