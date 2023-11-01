<?php

namespace App\Tests;

use App\DataFixtures\AppFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

trait TestUtilsTrait
{
    protected EntityManagerInterface $em;

    protected function purgeDatabase(): void
    {
        // utils
        $this->em = self::getContainer()
            ->get('doctrine')
            ->getManager();

        // reset database
        $purger = new ORMPurger($this->em, []);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $loader = new ContainerAwareLoader(self::getContainer());
        $paramBag = self::getContainer()->get(ParameterBagInterface::class);
        $loader->addFixture(new AppFixtures($paramBag));
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

    protected function terminateTest(): void
    {
        $this->em->close();
        unset($this->em);
    }
}
