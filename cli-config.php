<?php
declare(strict_types=1);

use DiscordBot\ContainerFactory;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$cb = new ContainerFactory();

$container = $cb->buildContainer();

// Create App
$entityManager = $container->get(EntityManager::class);
$connection = $entityManager->getConnection();

// Configure Doctrine
$configuration = new Configuration();
$configuration->setAllOrNothing(true);
$configuration->addMigrationsDirectory(
    'Bot',
    __DIR__ . '/migrations',
);

$dependencyFactory = DependencyFactory::fromEntityManager(
    new ExistingConfiguration($configuration),
    new ExistingEntityManager($entityManager),
);

$helperSet = new HelperSet();
$helperSet->set(new EntityManagerHelper($entityManager), 'em');
$helperSet->set(new QuestionHelper(), 'dialog');

// Create Application
$cli = ConsoleRunner::createApplication($helperSet, [
    new GenerateCommand($dependencyFactory),
    new MigrateCommand($dependencyFactory)
]);

$cli->setCatchExceptions(true);

$cli->run();