<?php

namespace Albacode\Famille;


use Albacode\Famille\Exception\DeuxHommesPasDenfantsException;
use Albacode\Famille\Exception\EnfantHorsMariageException;
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
     * @param Homme|Femme $H1
     * @param Homme|Femme $H2
     * @return $this
     */
    public function addMariage($H1, $H2)
    {
        if ($H1 instanceof Femme ){
            if($H2 instanceof Femme ){
                $value1 = new Epouse($H2,null);
                $value2 = new Epouse($H1,null);
            }
        }
        if ($H1 instanceof Homme ){
            if($H2 instanceof Femme ){
                $value1 = new Epoux($H2,null);
                $value2 = new Epouse($H1,null);
            }
        }

        if ($H1 instanceof Femme ){
            if($H2 instanceof Homme ){
                $value1 = new Epouse($H2,null);
                $value2 = new Epoux($H1,null);
            }
        }

        if ($H1 instanceof Homme ){
            if($H2 instanceof Homme ){
                $value1 = new Epoux(null,$H2);
                $value2 = new Epoux(null,$H1);
            }
        }

        if(isset($value2,$value1)){
            $this->addMembre($H1);
            $H1->addRole($value1);
            $this->addMembre($H2);

            $H2->addRole($value2);
        }

        return $this;
    }

    /**
     * @param Femme|Homme $H1
     * @param Femme|Homme $H2
     * @param MembreInterface $enfant
     * @return $this
     * @throws DeuxHommesPasDenfantsException
     */
    public function addNaissance($H1, $H2, MembreInterface $enfant)
    {
        if ( ($H1 instanceof Homme) && ($H2 instanceof Homme)) {
            throw new DeuxHommesPasDenfantsException('Un couple d\'hommes ne peuvent pas avoir d\'enfants !');
        }
        $this
            ->addMembre($H1)
            ->addMembre($H2)
            ->addMembre($enfant);
        $H1->addRole(new Mere($enfant));
        $H2->addRole(($H2 instanceof Femme) ? new Mere($enfant) : new Pere($enfant));
        $enfant->addRole(new Enfant($H1, $H2));
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
