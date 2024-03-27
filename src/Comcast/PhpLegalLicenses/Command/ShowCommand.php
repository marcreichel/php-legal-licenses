<?php

declare(strict_types=1);

namespace Comcast\PhpLegalLicenses\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowCommand extends DependencyLicenseCommand
{
    /**
     * Configure the command options.
     */
    final protected function configure(): void
    {
        $this
        ->setName('show')
        ->setDescription('Show licenses used by project dependencies.');
    }

    /**
     * Execute the command.
     */
    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dependencies = $this->getDependencyList();
        $this->outputDependencyLicenses($dependencies, $output);

        return self::SUCCESS;
    }

    /**
     * Generates Licenses list using packages retrieved from composer.lock file.
     */
    final protected function outputDependencyLicenses(array $dependencies, OutputInterface $output): void
    {
        foreach ($dependencies as $dependency) {
            $text = $this->getTextForDependency($dependency);
            $output->writeln($text);
        }
    }

    /**
     * Retrieves text containing version and license information for the specified dependency.
     */
    final protected function getTextForDependency(array $dependency): string
    {
        $name = $dependency['name'];
        $version = $dependency['version'];
        $licenseNames = isset($dependency['license']) ? implode(', ', $dependency['license']) : 'Not configured.';

        return $this->generateDependencyText($name, $version, $licenseNames);
    }

    /**
     * Generate formatted line detailing the version and license information for a particular dependency.
     */
    final protected function generateDependencyText(string $name, string $version, string $licenseNames): string
    {
        return "$name@$version [$licenseNames]";
    }
}
