<?php

namespace Albacode\Famille\Membre;

class Homme extends AbstractMembre
{

    /**
     * @inheritdoc
     */
    public function getGenre()
    {
        return 'masculin';
    }
}
