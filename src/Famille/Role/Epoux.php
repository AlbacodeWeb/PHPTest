<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\AbstractMembre;


class Epoux implements RoleInterface
{

    /**
     * @var Conjoint
     */
    protected $conjoint;

    /**
     * Enfant constructor.
     * @param Femme $epouse
     */
    public function __construct(AbstractMembre $conjoint)
    {
        $this->conjoint = $conjoint;
    }
}
