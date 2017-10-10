<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\AbstractMembre;

class Epouse implements RoleInterface
{

    /**
     * @var Conjoint
     */
    protected $conjoint;

    /**
     * Enfant constructor.
     * @param Homme $epoux
     */
    public function __construct(AbstractMembre $conjoint)
    {
        $this->conjoint = $conjoint;
    }
}
