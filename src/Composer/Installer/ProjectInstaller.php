<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\Installer;

use Composer\Package\PackageInterface;
use Composer\Downloader\DownloadManager;
use Composer\Repository\InstalledRepositoryInterface;

/**
 * Project Installer is used to install a single package into a directory as
 * root project.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
class ProjectInstaller implements InstallerInterface
{
    private $installPath;
    private $downloadManager;

    public function __construct($installPath, DownloadManager $dm)
    {
        $this->installPath = $installPath;
        $this->downloadManager = $dm;
    }

    /**
     * Decides if the installer supports the given type
     *
     * @param  string $packageType
     * @return bool
     */
    public function supports($packageType)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isInstalled(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $installPath = $this->installPath;
        if (file_exists($installPath)) {
            throw new \InvalidArgumentException("Project directory $installPath already exists.");
        }
        if (!file_exists(dirname($installPath))) {
            throw new \InvalidArgumentException("Project root " . dirname($installPath) . " does not exist.");
        }
        mkdir($installPath, 0777);
        $this->downloadManager->download($package, $installPath);
    }

    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        throw new \InvalidArgumentException("not supported");
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        throw new \InvalidArgumentException("not supported");
    }

    /**
     * Returns the installation path of a package
     *
     * @param  PackageInterface $package
     * @return string           path
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->installPath;
    }
}
