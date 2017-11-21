<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;

class Enfant implements RoleInterface
{

    /**
     * @var Femme|Homme
     */
    protected $mere;

    /**
     * @var Homme|Femme
     */
    protected $pere;

    public function __construct($mere, $pere)
    {
        $this->mere = $mere;
        $this->pere = $pere;
    }
}
