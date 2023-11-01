<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    private string $fixturesPath;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->fixturesPath = $parameterBag->get('app.fixtures_path');
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new AliceProvider());
        $loader = new NativeLoader($faker);

        // system
        $os = $loader->loadFile($this->fixturesPath.'/app.yml');
        foreach ($os->getObjects() as $o) {
            $manager->persist($o);
        }

        $manager->flush();
    }
}
