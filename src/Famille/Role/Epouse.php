<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;

class Epouse implements RoleInterface
{

    /**
     * @var Homme
     */
    protected $epoux;


    /**
     * @var Femme
     */
    protected $epouse;

    /**
     * Enfant constructor.
     * @param Homme $epoux
     * @param Femme $epouse
     */
    public function __construct($epoux, $epouse)
    {
        $this->epoux = $epoux;
        $this->epouse = $epouse;
    }
}
