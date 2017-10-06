<?php

namespace Albacode\Famille\Membre;


use Doctrine\Common\Collections\ArrayCollection;

class MembreCollection extends ArrayCollection
{
    /**
     * @param MembreInterface $membre
     * @return $this
     */
    public function addMembre(MembreInterface $membre) {
        $this->add($membre);
        return $this;
    }

    /**
     * @param MembreInterface $membre
     * @return $this
     */
    public function removeMembre(MembreInterface $membre) {
        $this->remove($membre);
        return $this;
    }

}
