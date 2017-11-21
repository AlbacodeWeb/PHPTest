<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;

class Epoux implements RoleInterface
{

    /**
     * @var Femme
     */
    protected $epouse;

    /**
     * @var Homme
     */
    protected $epoux;

    /**
     * Enfant constructor.
     * @param Homme $epoux
     * @param Femme $epouse
     */
    public function __construct($epouse, $epoux)
    {
        $this->epouse = $epouse;
        $this->epoux = $epoux;
    }
}
