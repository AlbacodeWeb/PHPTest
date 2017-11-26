<?php

namespace Albacode\Famille;

use Albacode\Famille\Membre\AbstractMembre;
use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;
use Albacode\Famille\Membre\MembreCollection;
use Albacode\Famille\Membre\MembreInterface;
use Albacode\Famille\Role\Enfant;
use Albacode\Famille\Role\Epouse;
use Albacode\Famille\Role\Epoux;
use Albacode\Famille\Role\Mere;
use Albacode\Famille\Role\Pere;

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
     * @param AbstractMembre $membre1
     * @param AbstractMembre $membre2
     * @return $this
     */
    public function addMariage(MembreInterface $membre1, MembreInterface $membre2)
    {
        $this->addMembre($membre1);
        $membre1->marryTo($membre2);
        $this->addMembre($membre2);
        $membre2->marryTo($membre1);
        return $this;
    }

    /**
     * @param Femme $mere
     * @param Homme $pere
     * @param MembreInterface $enfant
     * @return $this
     */
    public function addNaissance(Femme $mere, MembreInterface $parent, MembreInterface $enfant)
    {
        $this->addMembre($enfant);
        $mere->addRole(new Mere($enfant));
        if($parent instanceof Homme)
            $parent->addRole(new Pere($enfant));
        else
            $parent->addRole(new Mere($enfant));
        $enfant->addRole(new Enfant($mere, $parent));
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
    public function getMeres()
    {
        return $this->getMembresByRoleClass(Mere::class);
    }

    /**
     * @return MembreCollection
     */
    public function getPeres()
    {
        return $this->getMembresByRoleClass(Pere::class);
    }

    /**
     * @return MembreCollection
     */
    public function getParents()
    {
        return $this->getMembresByRolesClass(array(Mere::class, Pere::class));
    }

    /**
     * @return MembreCollection
     */
    public function getEpouses()
    {
        return $this->getMembresByRoleClass(Epouse::class);
    }

    /**
     * @return MembreCollection
     */
    public function getEpoux()
    {
        return $this->getMembresByRoleClass(Epoux::class);
    }

    /**
     * @return MembreCollection
     */
    public function getEnfants()
    {
        return $this->getMembresByRoleClass(Enfant::class);
    }

    /**
     * @return MembreCollection
     */
    public function getConjoints()
    {
        return $this->getMembresByRolesClass(array(Epouse::class, Epoux::class));
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return [
            'nbMembres' => $this->getMembres()->count(),
            'nbMeres' => $this->getMeres()->count(),
            'nbPeres' => $this->getPeres()->count(),
            'nbEpouses' => $this->getEpouses()->count(),
            'nbEpoux' => $this->getEpoux()->count(),
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

    protected function getMembresByRolesClass(array $classNames)
    {
        return $this->membres->filter(function(MembreInterface $membre) use ($classNames) {

            foreach ($classNames as $className)
                if ( $membre->hasRole($className) )
                    return true;

            return false;
        });
    }
}
