<?php

namespace Albacode\Famille;


use Albacode\Famille\Exception\EnfantHorsMariageException;
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
     * @param Femme $femme
     * @param Homme $homme
     * @return $this
     */
    public function addMariage(Femme $femme, Homme $homme)
    {
        $this->addMembre($femme);
        $femme->addRole(new Epouse($homme));
        $this->addMembre($homme);
        $homme->addRole(new Epoux($femme));
        return $this;
    }

    /**
     * @param Femme $mere
     * @param Homme $pere
     * @param MembreInterface $enfant
     * @return $this
     * @throws EnfantHorsMariageException
     */
    public function addNaissance(Femme $mere, Homme $pere, MembreInterface $enfant)
    {
        if (!$mere->hasRole(Epouse::class) || !$pere->hasRole(Epoux::class)) {
            throw new EnfantHorsMariageException('Les parents doivent être mariés pour pouvoir avoir un enfant !');
        }
        $this->addMembre($enfant);
        $mere->addRole(new Mere($enfant));
        $pere->addRole(new Pere($enfant));
        $enfant->addRole(new Enfant($mere, $pere));
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
}
