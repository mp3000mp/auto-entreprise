<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Nelmio\Alice\ObjectSet;
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
        // system
        $objectSet = $this->loadConfig($manager, 'app1.yml');
        // fixtures
        $this->loadConfig($manager, 'app2.yml', $objectSet->getParameters(), $objectSet->getObjects());
        // files
        file_put_contents($this->fixturesPath.'/file.txt', 'fake file');
        // todo link file to tender/opportunity
    }

    /**
     * @param mixed[] $parameters
     * @param mixed[] $objects
     */
    public function loadConfig(ObjectManager $manager, string $name, array $parameters = [], array $objects = []): ObjectSet
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new AliceProvider());
        $loader = new NativeLoader($faker);

        $os = $loader->loadFile($this->fixturesPath.'/'.$name, $parameters, $objects);
        foreach ($os->getObjects() as $o) {
            $manager->persist($o);
        }
        $manager->flush();

        return $os;
    }
}
