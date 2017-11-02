<?php

namespace Albacode\Famille;


use Albacode\Famille\Exception\EnfantCoupleHommeException;
use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;
use Albacode\Famille\Membre\MembreCollection;
use Albacode\Famille\Membre\MembreInterface;
use Albacode\Famille\Role\Enfant;
use Albacode\Famille\Role\Conjoint_e;
use Albacode\Famille\Role\Parents;


class Famille
{
    /**
     * @var MembreCollection
     */
    protected $membres;

    public function __construct()
    {
        $this->membres = new MembreCollection();
    }

    /**
     * @param MembreInterface $membre
     * @return $this
     */
    public function addMembre(MembreInterface $membre)
    {
        $this->membres->set($membre->getNom(), $membre);
        return $this;
    }

    /**
     * @param MembreInterface $conjoint_e1
     * @param MembreInterface $conjoint_e2
     * @return $this
     */
    public function addMariage(MembreInterface $conjoint_e1, MembreInterface $conjoint_e2)
    {
        $this->addMembre($conjoint_e1);
        $conjoint_e1->addRole(new Conjoint_e($conjoint_e2));
        $this->addMembre($conjoint_e2);
        $conjoint_e2->addRole(new Conjoint_e($conjoint_e1));
        return $this;
    }

    /**
     * @param MembreInterface $parent_1
     * @param MembreInterface $parent_2
     * @param MembreInterface $enfant
     * @return $this
     * @throws EnfantHorsMariageException
     */
    public function addNaissance(MembreInterface $parent_1, MembreInterface $parent_2, MembreInterface $enfant)
    {
        if ($parent_1 instanceof Homme && $parent_2 instanceof Homme) {
          throw new EnfantCoupleHommeException('Les couples d\'hommes ne peuvent pas avoir d\'enfant. (et les adoptions ?)') ;
        }
        $this->addMembre($enfant);
        $parent_1->addRole(new Parents($enfant));
        $parent_2->addRole(new Parents($enfant));
        $enfant->addRole(new Enfant($parent_1, $parent_2));
        return $this;
    }

    /**
     * @return MembreCollection
     */
    public function getMembres()
    {
        return $this->membres;
    }
    /**
    * @return MembreCollection
    */
    public function getParents()
    {
      return $this->getMembresByRoleClass(Parents::class);
    }
    /**
    * @return MembreCollection
    */
    public function getConjoint_es()
    {
      return $this->getMembresByRoleClass(Conjoint_e::class);
    }
    /**
     * @return MembreCollection
     */
    public function getEnfants()
    {
        return $this->getMembresByRoleClass(Enfant::class);
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return [
            'nbMembres' => $this->getMembres()->count(),
            'nbParents' => $this->getParents()->count(),
            'nbConjoint_es' => $this->getConjoint_es()->count(),
            'nbEnfants' => $this->getEnfants()->count(),
        ];
    }

    /**
     * @param $className
     * @return MembreCollection
     */
    protected function getMembresByRoleClass($className)
    {
        return $this->membres->filter(function(MembreInterface $membre) use ($className) {
            return $membre->hasRole($className);
        });
    }
}
