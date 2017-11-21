<?php

namespace Tests\Famille;

use Albacode\Famille\Exception\DeuxHommesPasDenfantsException;
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

    public function addMariageSameSexeDataProvider()
    {
        return [[
            new Femme('Ep1'),
            new Femme('Ep2'),
            2,
            2
        ],[
            new Homme('Ep1'),
            new Homme('Ep2'),
            2,
            2
        ]];
    }

    /**
     * @dataProvider addMariageSameSexeDataProvider
     * @param Femme|Homme $ep1
     * @param Femme|Homme $ep2
     * @param $expectedMembreCount
     */
    public function testAddMariageSameSexe(
        $ep1,
        $ep2,
        $expectedMembreCount,
        $expectSameSexeCount
    ) {
        $famille = (new Famille())
            ->addMariage($ep1, $ep2)
        ;
        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertThat($expectSameSexeCount,$this->logicalOr(
            $this->equalTo($famille->getEpouses()->count()),
            $this->equalTo($famille->getEpoux()->count())
        ));
    }

    public function addNaissanceDataProvider()
    {
        return [[
            new Femme('Mère'),
            new Homme('Père'),
            new Homme('Enfant'),
            3,
            1,
            1
        ],[
            new Homme('p1'),
            new Homme('p2'),
            new Femme('Enfant'),
            3,
            0,
            2
        ],[
            new Femme('m1'),
            new Femme('m2'),
            new Homme('Enfant'),
            3,
            2,
            0
        ],];
    }

    /**
     * @dataProvider addNaissanceDataProvider
     * @param Femme|Homme $H1
     * @param Homme|Femme $H2
     * @param MembreInterface $enfant
     * @param $expectedMembreCount
     * @param $expectedMeresCount
     * @param $expectedPeresCount
     * @throws \Albacode\Famille\Exception\DeuxHommesPasDenfantsException
     */
    public function testAddNaissance(
        $H1,
        $H2,
        MembreInterface $enfant,
        $expectedMembreCount,
        $expectedMeresCount,
        $expectedPeresCount
    ) {

        $famille = (new Famille())
            ->addMembre($H1)
            ->addMariage($H1, $H2);

        if($H1 instanceof Homme && $H2 instanceof Homme){
            $this->expectException(DeuxHommesPasDenfantsException::class);
            $famille->addNaissance($H1, $H2, $enfant);
        }
            $famille->addNaissance($H1, $H2, $enfant);
            $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
            $this->assertEquals($expectedMeresCount, $famille->getMeres()->count());
            $this->assertEquals($expectedPeresCount, $famille->getPeres()->count());
            $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
            $this->assertEquals($H1->getNom(), $famille->getMeres()->first()->getNom());
            $this->assertThat($H2->getNom(),$this->logicalOr(
                $this->equalTo(($famille->getPeres() && $famille->getPeres()->first()) ? $famille->getPeres()->first()->getNom() : ''),
                $this->equalTo( ($famille->getMeres() && $famille->getMeres()->last()) ? $famille->getMeres()->last()->getNom() : '')
            ));
            $this->assertEquals($enfant->getNom(), $famille->getEnfants()->first()->getNom());
    }


    public function addNaissanceHorsMariageDataProvider()
    {
        return [[
            new Homme('Père A'),
            new Femme('Mère A'),
            new Homme('Enfant A')
        ]];
    }

    /**
     * @dataProvider addNaissanceHorsMariageDataProvider
     * @param Homme|Femme $pere
     * @param Femme|Homme $mere
     * @param MembreInterface $enfant
     */
    public function testAddNaissanceHorsMariage($pere, $mere, $enfant)
    {

        $famille = new Famille();
        $famille->addMembre($pere)
            ->addMembre($mere);

        $famille->addNaissance($mere, $pere, $enfant);

        $this->assertTrue(true);

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
            ]
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




    public function getStatsMixDataProvider()
    {
        $grandPereA = new Homme('Grand-Père A');
        $grandMereA = new Femme('Grand-Mère A');
        $mereA = new Femme('Mère A');
        $mereB = new Femme('Mère B');
        $pereA = new Homme('Père A');
        $enfantC = new Femme('Enfant C');

        return [
            [
                (new Famille())
                    ->addMembre($grandMereA)
                    ->addNaissance($grandMereA, $grandPereA, $mereA)
                    ->addMariage($mereA, $mereB)
                    ->addNaissance($mereA, $pereA, $enfantC),
                [
                    'nbMembres' => 6,
                    'nbMeres' => 2,
                    'nbPeres' => 2,
                    'nbEpouses' => 2,
                    'nbEpoux' => 0,
                    'nbEnfants' => 2,
                ],
            ]
        ];
    }

    /**
     * @dataProvider getStatsMixDataProvider
     * @param Famille $famille
     * @param array $stats
     */
    public function testGetStatsMix(Famille $famille, array $stats)
    {
        $this->assertEquals($famille->getStats(), $stats);
    }
}
