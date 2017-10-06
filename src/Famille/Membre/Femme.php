<?php

namespace Albacode\Famille\Membre;

class Femme extends AbstractMembre
{
    /**
     * @inheritdoc
     */
    public function getGenre()
    {
        return 'feminin';
    }

}
