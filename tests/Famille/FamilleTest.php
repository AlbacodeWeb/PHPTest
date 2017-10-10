<?php

namespace Tests\Famille;

use Albacode\Famille\Famille;
use Albacode\Famille\Membre\Femme;
use Albacode\Famille\Membre\Homme;
use Albacode\Famille\Membre\MembreInterface;
use PHPUnit\Framework\TestCase;
use Albacode\Famille\Exception\EnfantCoupleHommeException;

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
        $this->assertEquals($epouse->getNom(), $famille->getPartenaires()->first()->getNom());
        $this->assertEquals($epoux->getNom(), $famille->getPartenaires()->last()->getNom());
    }

    public function addMariageHommeDataProvider()
    {
      return [[
          new Homme('Homme 1'),
          new Homme('Homme 2'),
          2
        ]];
    }
    /**
     * @dataProvider addMariageHommeDataProvider
     * @param Homme $homme1
     * @param Homme $homme2
     * @param $expectedMembreCount
     */
    public function testAddMariageHomme(
        Homme $homme1,
        Homme $homme2,
        $expectedMembreCount
    ) {
      $famille = (new Famille())
          ->addMembre($homme1)
          ->addMariage($homme1,$homme2)
      ;
      $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
      $this->assertEquals($homme1->getNom(), $famille->getPartenaires()->first()->getNom());
      $this->assertEquals($homme2->getNom(), $famille->getPartenaires()->last()->getNom());


    }

    public function addMariageFemmeDataProvider()
    {
      return [[
          new Femme('Femme 1'),
          new Femme('Femme 2'),
          2
        ]];
    }
    /**
     * @dataProvider addMariageFemmeDataProvider
     * @param Femme $femme1
     * @param Femme $femme2
     * @param $expectedMembreCount
     */
    public function testAddMariageFemme(
        Femme $femme1,
        Femme $femme2,
        $expectedMembreCount
    ) {
      $famille = (new Famille())
          ->addMembre($femme1)
          ->addMariage($femme1,$femme2)
      ;
      $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
      $this->assertEquals($femme1->getNom(), $famille->getPartenaires()->first()->getNom());
      $this->assertEquals($femme2->getNom(), $famille->getPartenaires()->last()->getNom());


    }


    public function addNaissanceFemmesDataProvider()
    {
        return [[
            new Femme('Mère 1'),
            new Femme('Mère 2'),
            new Homme('Enfant'),
            3
        ]];
    }

    /**
     * @dataProvider addNaissanceFemmesDataProvider
     * @param Femme $mere1
     * @param Femme $mere2
     * @param MembreInterface $enfant
     * @param $expectedMembreCount
     */
    public function testAddNaissanceFemmes(
        Femme $mere1,
        Femme $mere2,
        MembreInterface $enfant,
        $expectedMembreCount
    ) {

        $famille = (new Famille())
            ->addMembre($mere1)
            ->addMembre($mere2)
            ->addNaissance($mere1, $mere2, $enfant);

        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($mere1->getNom(), $famille->getParentFamille()->first()->getNom());
        $this->assertEquals($mere2->getNom(), $famille->getParentFamille()->last()->getNom());
        $this->assertEquals($enfant->getNom(), $famille->getEnfants()->first()->getNom());
    }

    public function addNaissanceHommesDataProvider()
    {
        return [[
            new Homme('Père 1'),
            new Homme('Père 2'),
            new Homme('Enfant'),
            3
        ]];
    }

    /**
     * @dataProvider addNaissanceHommesDataProvider
     * @expectedException \Albacode\Famille\Exception\EnfantCoupleHommeException
     * @param Homme $pere1
     * @param Homme $pere2
     * @param MembreInterface $enfant
     * @param $expectedMembreCount
     * @throws \Albacode\Famille\Exception\EnfantCoupleHommeException
     */
    public function testAddNaissanceHommes(
        Homme $pere1,
        Homme $pere2,
        MembreInterface $enfant,
        $expectedMembreCount
    ) {

        $famille = (new Famille())
            ->addMembre($pere1)
            ->addMembre($pere2)
            ->addNaissance($pere1, $pere2, $enfant);

        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($pere1->getNom(), $famille->getParentFamille()->first()->getNom());
        $this->assertEquals($pere2->getNom(), $famille->getParentFamille()->last()->getNom());
        $this->assertEquals($enfant->getNom(), $famille->getEnfants()->first()->getNom());
    }

    public function addNaissanceSansMariageDataProvider()
    {
        return [[
            new Femme('Mère'),
            new Homme('Père'),
            new Homme('Enfant'),
            3
        ]];
    }

    /**
     * @dataProvider addNaissanceSansMariageDataProvider
     * @param Femme $mere
     * @param Homme $pere
     * @param MembreInterface $enfant
     * @param $expectedMembreCount
     */
    public function testAddNaissanceSansMariage(
        Femme $mere,
        Homme $pere,
        MembreInterface $enfant,
        $expectedMembreCount
    ) {

        $famille = (new Famille())
            ->addMembre($mere)
            ->addMembre($pere)
            ->addNaissance($mere, $pere, $enfant);

        $this->assertEquals($expectedMembreCount, $famille->getMembres()->count());
        $this->assertEquals($mere->getNom(), $famille->getParentFamille()->first()->getNom());
        $this->assertEquals($pere->getNom(), $famille->getParentFamille()->last()->getNom());
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
                    'nbParents' => 0,
                    'nbPartenaires' => 0,
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
                    'nbParents' => 4,
                    'nbPartenaires' => 4,
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
                    'nbParents' => 4,
                    'nbPartenaires' => 4,
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
