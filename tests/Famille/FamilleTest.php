<?php

namespace Tests\Famille;

use Albacode\Famille\Famille;
use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;
use Albacode\Famille\Membre\MembreInterface;
use PHPUnit\Framework\TestCase;

class FamilleTest extends TestCase
{
    /*
     * Fournisseur de donnée test
     * pour un mariage entre homme et femme
     */
    public function addMariageDataProvider()
    {
        return [[
            new Femme('Epouse'),
            new Homme('Epoux'),
            2
        ]];
    }

    /*
     * Fournisseur de donnée test 
     * pour un mariage entre femme
     */
    public function addMariageFemmeDataProvider()
    {
        return [[
            new Femme('EpouseUne'),
            new Femme('EpouseDeux'),
            2
        ]];
    }
    
     /*
     * Fournisseur de donnée test 
     * pour un mariage entre homme
     */
    public function addMariageHommeDataProvider()
    {
        return [[
            new Homme('EpouxUn'),
            new Homme('EpouxDeux'),
            2
        ]];
    }
    
    /**
     * @dataProvider addMariageDataProvider
     * @param Femme $epouse
     * @param Homme $epoux
     * @param $expectedMembreCount
     */
    public function testAddMariage(
        Femme $epouse,
        Homme $epoux,
        $expectedMembreCount
    ) {
        $famille = (new Famille())
            ->addMembre($epouse)
            ->addMariage($epouse, $epoux)
        ;
        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($epouse->getNom(), $famille->getEpouses()->first()->getNom());
        $this->assertEquals($epoux->getNom(), $famille->getEpoux()->first()->getNom());
    }
    
    /**
     * @dataProvider addMariageFemmeDataProvider
     * @param Femme $epouseUne
     * @param Femme $epouseDeux
     * @param $expectedMembreCount
     */
    public function testAddMariageFemme(
            Femme $epouseUne, 
            Femme $epouseDeux,
            $expectedMembreCount
    ){
        
        $famille = (new Famille())
            ->addMembre($epouseUne)
            ->addMariageFemme($epouseUne, $epouseDeux)
        ;
        
        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($epouseUne->getNom(), $famille->getEpouses()->first()->getNom());
        $this->assertEquals($epouseDeux->getNom(), $famille->getEpouses()->next()->getNom());
    }
    
    /**
     * @dataProvider addMariageHommeDataProvider
     * @param Homme $epouxUn
     * @param Homme $epouxDeux
     * @param $expectedMembreCount
     */
    public function testAddMariageHomme(
            Homme $epouxUn, 
            Homme $epouxDeux,
            $expectedMembreCount
    ){
        
        $famille = (new Famille())
            ->addMembre($epouxUn)
            ->addMariageHomme($epouxUn, $epouxDeux)
        ;
        
        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($epouxUn->getNom(), $famille->getEpoux()->first()->getNom());
        $this->assertEquals($epouxDeux->getNom(), $famille->getEpoux()->next()->getNom());
    }
    
    /*
     * Fournisseur de donner de test 
     * pour un mariage homme femme et qui veulent un enfant
     */
    public function addNaissanceDataProvider()
    {
        return [[
            new Femme('Mère'),
            new Homme('Père'),
            new Homme('Enfant'),
            3
        ]];
    }

    /*
     * Fournisseur de donnée pour un mariage
     * entre femme et qui veulent un enfant
     */
    public function addNaissancFemmeDataProvider()
    {
        return [[
            new Femme('MéreUne'),
            new Femme('MéreDeux'),
            new Homme('Enfant'),
            3
        ]];
    }
    
    /**
     * @dataProvider addNaissanceDataProvider
     * @param Femme $mere
     * @param Homme $pere
     * @param MembreInterface $enfant
     * @param $expectedMembreCount
     * @throws \Albacode\Famille\Exception\EnfantHorsMariageException
     */
    public function testAddNaissance(
        Femme $mere,
        Homme $pere,
        MembreInterface $enfant,
        $expectedMembreCount
    ) {

        $famille = (new Famille())
            ->addMembre($mere)
            ->addMariage($mere, $pere)
            ->addNaissance($mere, $pere, $enfant);

        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($mere->getNom(), $famille->getMeres()->first()->getNom());
        $this->assertEquals($pere->getNom(), $famille->getPeres()->first()->getNom());
        $this->assertEquals($enfant->getNom(), $famille->getEnfants()->first()->getNom());
    }

    /**
     * @dataProvider addNaissancFemmeDataProvider
     * @param Femme $mereUne
     * @param Femme $mereDeux
     * @param MembreInterface $enfant
     * @param $expectedMembreCount
     * @throws \Albacode\Famille\Exception\EnfantHorsMariageException
     */
    public function testAddNaissanceFemme(
        Femme $mereUne,
        Femme $mereDeux,
        MembreInterface $enfant,
        $expectedMembreCount
    ) {

        $famille = (new Famille())
            ->addMembre($mereUne)
            ->addMembre($mereDeux)
            ->addNaissance($mereUne, $mereDeux, $enfant);

        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($mereUne->getNom(), $famille->getMeres()->first()->getNom());
        $this->assertEquals($mereDeux->getNom(), $famille->getMeres()->next()->getNom());
        $this->assertEquals($enfant->getNom(), $famille->getEnfants()->first()->getNom());
    }
    /**
     * @expectedException \Albacode\Famille\Exception\EnfantCoupleHommeMariageException
     */
    public function testAddNaissanceHommeThrowsException()
    {
        $famille = new Famille();
        $pereUn = new Homme('Père Un');
        $pereDeux = new Homme('Pére Deux');
        $famille->addMembre($pereUn)
            ->addMembre($pereDeux);
        $enfant = new Homme('Enfant A');
        $famille->addNaissance($pereUn, $pereDeux, $enfant);
    }
    
    public function getStatsDataProvider()
    {
        $grandPereA = new Homme('Grand-Père A');
        $grandMereA = new Femme('Grand-Mère A');
        $mereA = new Femme('Mère A');
        $pereA = new Homme('Père A');
        $enfantA = new Homme('Enfant A');
        $enfantB = new Homme('Enfant B');

        return [
            [
                new Famille(),
                [
                    'nbMembres' => 0,
                    'nbMeres' => 0,
                    'nbPeres' => 0,
                    'nbEpouses' => 0,
                    'nbEpoux' => 0,
                    'nbEnfants' => 0,
                ],
            ],
            [
                (new Famille())
                    ->addMembre($grandMereA)
                    ->addMariage($grandMereA, $grandPereA)
                    ->addNaissance($grandMereA, $grandPereA, $mereA)
                    ->addMariage($mereA, $pereA)
                    ->addNaissance($mereA, $pereA, $enfantA),
                [
                    'nbMembres' => 5,
                    'nbMeres' => 2,
                    'nbPeres' => 2,
                    'nbEpouses' => 2,
                    'nbEpoux' => 2,
                    'nbEnfants' => 2,
                ],
            ],
            [
                (new Famille())
                    ->addMembre($grandMereA)
                    ->addMariage($grandMereA, $grandPereA)
                    ->addNaissance($grandMereA, $grandPereA, $mereA)
                    ->addMariage($mereA, $pereA)
                    ->addNaissance($mereA, $pereA, $enfantA)
                    ->addNaissance($grandMereA, $grandPereA, $enfantB),
                [
                    'nbMembres' => 6,
                    'nbMeres' => 2,
                    'nbPeres' => 2,
                    'nbEpouses' => 2,
                    'nbEpoux' => 2,
                    'nbEnfants' => 3,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getStatsDataProvider
     * @param Famille $famille
     * @param array $stats
     */
    public function testGetStats(Famille $famille, array $stats)
    {
        $this->assertEquals($famille->getStats(), $stats);
    }
}
