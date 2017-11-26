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
        return [
            [
                new Femme('Epouse'),
                new Homme('Epoux'),
                2
            ],
            [
                new Femme('Epouse 1'),
                new Femme('Epouse 2'),
                2
            ],
            [
                new Homme('Epoux 1'),
                new Homme('Epoux 2'),
                2
            ],

        ];
    }

    /**
     * @dataProvider addMariageDataProvider
     * @param MembreInterface $membre1
     * @param MembreInterface $membre2
     * @param $expectedMembreCount
     */
    public function testAddMariage(
        MembreInterface $membre1,
        MembreInterface $membre2,
        $expectedMembreCount
    ) {
        $famille = (new Famille())
            ->addMembre($membre1)
            ->addMariage($membre1, $membre2)
        ;
        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($expectedMembreCount, $famille->getConjoints()->count());
        $this->assertEquals($membre1->getNom(), $famille->getConjoints()->first()->getNom());
        $this->assertEquals($membre2->getNom(), $famille->getConjoints()->next()->getNom());
    }

    public function addNaissanceDataProvider()
    {
        return [
            [
                new Femme('Mère'),
                new Homme('Père'),
                new Homme('Enfant'),
                3
            ],
            [
                new Femme('Mère'),
                new Femme('Mère Adoptive'),
                new Femme('Enfant'),
                3
            ],
        ];
    }

    /**
     * @dataProvider addNaissanceDataProvider
     * @param Femme $mere
     * @param MembreInterface $conjoint
     * @param MembreInterface $enfant
     * @param $expectedMembreCount
     */
    public function testAddNaissance(
        Femme $mere,
        MembreInterface $conjoint,
        MembreInterface $enfant,
        $expectedMembreCount
    ) {

        $famille = (new Famille())
            ->addMembre($mere)
            ->addMariage($mere, $conjoint)
            ->addNaissance($mere, $conjoint, $enfant);

        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($mere->getNom(), $famille->getParents()->first()->getNom());
        $this->assertEquals($conjoint->getNom(), $famille->getParents()->next()->getNom());
        $this->assertEquals($enfant->getNom(), $famille->getEnfants()->first()->getNom());
    }

    /**
     * @dataProvider addNaissanceDataProvider
     * @param Femme $mere
     * @param MembreInterface $conjoint
     * @param MembreInterface $enfant
     * @param $expectedMembreCount
     */
    public function testAddNaissanceHorsMariage(
        Femme $mere,
        MembreInterface $conjoint,
        MembreInterface $enfant,
        $expectedMembreCount
    ) {

        $famille = (new Famille())
            ->addMembre($mere)
            ->addMembre($conjoint)
            ->addNaissance($mere, $conjoint, $enfant);

        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($mere->getNom(), $famille->getParents()->first()->getNom());
        $this->assertEquals($conjoint->getNom(), $famille->getParents()->next()->getNom());
        $this->assertEquals($enfant->getNom(), $famille->getEnfants()->first()->getNom());
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
