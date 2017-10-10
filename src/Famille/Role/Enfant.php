<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\AbstractMembre;



class Enfant implements RoleInterface
{

    /**
     * @var membre (Homme ou Femme)
     */
    protected $parentUn;

    /**
     * @var Membre (Homme ou femme)
     */
    protected $parentDeux;

    public function __construct(AbstractMembre $parentUn, AbstractMembre $parentDeux)
    {
        $this->parentUn = $parentUn;
        $this->parentDeux = $parentDeux;
    }
}
