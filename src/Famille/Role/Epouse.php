<?php

namespace Albacode\Famille\Role;

use Albacode\Famille\Membre\Homme;

class Epouse implements RoleInterface
{

    /**
     * @var Homme
     */
    protected $epoux;

    /**
     * Enfant constructor.
     * @param Homme $epoux
     */
    public function __construct(Homme $epoux)
    {
        $this->epoux = $epoux;
    }
}
