<?php

namespace Tests\Famille;

use Albacode\Famille\Famille;
use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;
use Albacode\Famille\Membre\MembreInterface;
use PHPUnit\Framework\TestCase;

class FamilleTest extends TestCase
{

    public function addMariageDataProvider()
    {
        return [[
            new Femme('Epouse'),
            new Homme('Epoux'),
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

    public function addNaissanceDataProvider()
    {
        return [[
            new Femme('Mère'),
            new Homme('Père'),
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
     * @expectedException \Albacode\Famille\Exception\EnfantHorsMariageException
     */
    public function testAddNaissanceHorsMariageThrowsException()
    {

        $famille = new Famille();

        $pere = new Homme('Père A');
        $mere = new Femme('Mère A');

        $famille->addMembre($pere)
            ->addMembre($mere);

        $enfant = new Homme('Enfant A');

        $famille->addNaissance($mere, $pere, $enfant);
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
