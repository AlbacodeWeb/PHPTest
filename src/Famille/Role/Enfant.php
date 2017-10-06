<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;

class Enfant implements RoleInterface
{

    /**
     * @var Femme
     */
    protected $mere;

    /**
     * @var Homme
     */
    protected $pere;

    public function __construct(Femme $mere, Homme $pere)
    {
        $this->mere = $mere;
        $this->pere = $pere;
    }
}
