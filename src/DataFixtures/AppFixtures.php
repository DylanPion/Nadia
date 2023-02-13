<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Association;
use App\Entity\CompanySheet;
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


        for ($i = 0; $i < 50; $i++) {
            $association = new Association();
            $association->setName("Association n°$i");
            $manager->persist($association);

            $companySheet = new CompanySheet();
            $companySheet->setLoanStatus("En Cours")
                ->setAgreementNumber(mt_rand(1, 100))
                ->setCompanyName("Société n°$i")
                ->setDateOfCE($randomDate)
                ->setRepaymentStartDate($randomDate)
                ->setRepaymentEndDate($randomDate)
                ->setFNIAmountRequested(mt_rand(1, 200))
                ->setFniAmountPaid(mt_rand(1, 10000000))
                ->setPaymentOne(mt_rand(1, 50))
                ->setPaymentTwo(mt_rand(1, 50))
                ->setPaymentOneDate($randomDate)
                ->setPaymentTwoDate($randomDate)
                ->setTotalAmountRepaidToDate(mt_rand(1, 100000))
                ->setRemainsToBePaid()
                ->setProjectLeaderName1("Dylan")
                ->setProjectLeaderName2("Elias")
                ->setProjectLeaderName3("Nassim")
                ->setProjectLeaderName4("Cyril")
                ->setAssociation($association);


            $manager->persist($association);
            $manager->persist($companySheet);
        }
        $manager->flush();
    }
}
