<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Agreement;
use App\Entity\Association;
use App\Entity\CompanySheet;
use App\Entity\ProjectLeader;
use App\Entity\TotalAmountRepaidToDate;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // génération date aléatoire : 

        $start = new DateTime("2010-01-01");
        $end = new DateTime("2022-12-31");
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = new DateTime('@' . $randomTimestamp);

        for ($j = 0; $j < 6; $j++) {
            $agreement = new Agreement();
            $agreement->setNumber($j)
                ->setCashFund(mt_rand(1, 10000));
            $manager->persist($agreement);
        }

        // for ($j = 0; $j < 25; $j++) {
        //     $totalAmountRepaidToDate = new TotalAmountRepaidToDate();
        //     $totalAmountRepaidToDate->setPayment(mt_rand(1, 1000))
        //         ->setDate($randomDate)
        //         ->setTotalAmountRepaidToDate(0);
        //     $manager->persist($totalAmountRepaidToDate);
        // }

        for ($i = 0; $i < 5; $i++) {
            $association = new Association();
            $association->setName("Association n°$i");
            $manager->persist($association);
            for ($j = 0; $j < 2; $j++) {
                $companySheet = new CompanySheet();
                $companySheet->setLoanStatus("En Cours")
                    ->setCompanyName("Société n°$j")
                    ->setDateOfCE($randomDate)
                    ->setRepaymentStartDate($randomDate)
                    ->setRepaymentEndDate($randomDate)
                    ->setFNIAmountRequested(mt_rand(1, 200))
                    ->setFniAmountPaid(mt_rand(1, 1000))
                    ->setPaymentOne(mt_rand(1, 50))
                    ->setPaymentTwo(mt_rand(1, 50))
                    ->setPaymentOneDate($randomDate)
                    ->setPaymentTwoDate($randomDate)
                    ->setRemainsToBePaid(0)
                    // ->setRemainsToBeReceived(0)
                    ->setTotalFniAmountRequested(1)
                    ->setTotalFniAmountPaid(1)
                    ->setAgreement($agreement)
                    ->setAssociation($association);
                //     $projectleader = new ProjectLeader;
                //     $projectleader->setName("Membre n°$j")
                //         ->setCompanySheet($companySheet);
                //     $manager->persist($projectleader);
                $manager->persist($companySheet);
            }
        }
        $manager->flush();
    }
}
