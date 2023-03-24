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

        for ($i = 0; $i < 3; $i++) {
            $agreement = new Agreement;
            $agreement->setNumber($i)
                ->setCashFund(mt_rand(1, 10000));

            $association = new Association;
            $association->setName("Association n°$i");

            $companysheet = new CompanySheet;
            $companysheet->setAgreement($agreement)
                ->setAssociation($association)
                ->setCompanyName("Société n°$i")
                ->setDateOfCE($randomDate)
                ->setFniAmountRequested(mt_rand(1, 100000))
                ->setLoanStatus("En Cours")
                ->setPaymentOne(mt_rand(1, 1000000))
                ->setPaymentTwo(mt_rand(1, 1000000))
                ->setPaymentOneDate($randomDate)
                ->setPaymentTwoDate($randomDate)
                ->setRemainsToBePaid(0)
                ->setRemainsToBeReceived(0)
                ->setRepaymentStartDate($randomDate)
                ->setRepaymentEndDate($randomDate);

            $projectleader = new ProjectLeader;
            $projectleader->setName("Dylan")
                ->setCompanySheet($companysheet);

            $totalAmountRepaidToDate = new TotalAmountRepaidToDate;
            $totalAmountRepaidToDate->setCompanySheet($companysheet)
                ->setTotalAmountRepaidToDate(mt_rand(1, 10000))
                ->setDate($randomDate)
                ->setPayment(mt_rand(1, 10000));


            $manager->persist($totalAmountRepaidToDate);
            $manager->persist($projectleader);
            $manager->persist($association);
            $manager->persist($agreement);
            $manager->persist($companysheet);
        }
        $manager->flush();
    }
}
