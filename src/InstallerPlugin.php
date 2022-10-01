<?php

declare(strict_types=1);

namespace Yiisoft\YiiDevTool\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class InstallerPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $yiiPackages = [];
        $packages = array_merge($composer->getPackage()->getRequires(), $composer->getPackage()->getDevRequires());
        foreach ($packages as $packageName => $packageConfig) {
            if (str_starts_with($packageName, 'yiisoft/') && $packageName !== 'yiisoft/yii-dev-tool-installer') {
                $yiiPackages[$packageName] = $packageConfig->getConstraint()->getLowerBound()->getVersion();
            }
        }
        $composer->getRepositoryManager()->prependRepository(
            $composer->getRepositoryManager()->createRepository(
                'path',
                [
                    'type' => 'path',
                    'url' => '../*',
                    "options" => [
                        "versions" => $yiiPackages,
                    ],
                ],
            )
        );
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}
