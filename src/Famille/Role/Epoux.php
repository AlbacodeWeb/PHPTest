<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\Femme;

class Epoux implements RoleInterface
{

    /**
     * @var Femme
     */
    protected $epouse;

    /**
     * Enfant constructor.
     * @param Femme $epouse
     */
    public function __construct(Femme $epouse)
    {
        $this->epouse = $epouse;
    }
}
