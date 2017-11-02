<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\MembreInterface; 

class Conjoint_e implements RoleInterface
{

    /**
     * @var MembreInterface
     */
    protected $conjoint_e;

    /**
     * Enfant constructor.
     * @param MembreInterface $conjoint_e
     */
    public function __construct(MembreInterface $conjoint_e)
    {
        $this->conjoint_e = $conjoint_e;
    }
}
