<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\MembreInterface; 

class Partenaire implements RoleInterface
{

    /**
     * @var MembreInterface
     */
    protected $partenaire;

    /**
     * Enfant constructor.
     * @param MembreInterface $partenaire
     */
    public function __construct(MembreInterface $partenaire)
    {
        $this->partenaire = $partenaire;
    }
}
