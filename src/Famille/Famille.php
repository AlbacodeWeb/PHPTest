<?php

namespace Albacode\Famille;


use Albacode\Famille\Exception\EnfantCoupleHommeException;
use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;
use Albacode\Famille\Membre\MembreCollection;
use Albacode\Famille\Membre\MembreInterface;
use Albacode\Famille\Role\Enfant;
use Albacode\Famille\Role\Epouse;
use Albacode\Famille\Role\Epoux;
use Albacode\Famille\Role\Mere;
use Albacode\Famille\Role\Pere;
use Albacode\Famille\Role\ParentFamille;
use Albacode\Famille\Role\Partenaire;

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
     * @param MembreInterface $partenaire1
     * @param MembreInterface $partenaire2
     * @return $this
     */
    public function addMariage(MembreInterface $partenaire1, MembreInterface $partenaire2)
    {
        $this->addMembre($partenaire1);
        $partenaire1->addRole(new Partenaire($partenaire2));
        $this->addMembre($partenaire2);
        $partenaire2->addRole(new Partenaire($partenaire1));
        return $this;
    }

    /**
     * @param MembreInterface $parent1
     * @param MembreInterface $parent2
     * @param MembreInterface $enfant
     * @return $this
     * @throws EnfantHorsMariageException
     */
    public function addNaissance(MembreInterface $parent1, MembreInterface $parent2, MembreInterface $enfant)
    {
        if ($parent1 instanceof Homme && $parent2 instanceof Homme) {
          throw new EnfantCoupleHommeException('Seul un couple de femmes peut avoir un enfant. (c\'est étrange, mais c\'est l\'énoncé...)') ;
        }
        $this->addMembre($enfant);
        $parent1->addRole(new ParentFamille($enfant));
        $parent2->addRole(new ParentFamille($enfant));
        $enfant->addRole(new Enfant($parent1, $parent2));
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
    public function getParentFamille()
    {
      return $this->getMembresByRoleClass(ParentFamille::class);
    }
    /**
    * @return MembreCollection
    */
    public function getPartenaires()
    {
      return $this->getMembresByRoleClass(Partenaire::class);
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
            'nbParents' => $this->getParentFamille()->count(),
            'nbPartenaires' => $this->getPartenaires()->count(),
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
