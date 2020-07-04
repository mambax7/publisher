<?php

declare(strict_types=1);

namespace XoopsModules\Publisher\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Configurator Class
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      XOOPS Development Team
 * @since       1.05
 */

//use XoopsModules\Publisher;

/**
 * Class Configurator
 */
class Configurator
{
    public $name;
    public $paths = [];
    public $icons = [];
    public $uploadFolders = [];
    public $copyBlankFiles = [];
    public $copyTestFolders = [];
    public $templateFolders = [];
    public $oldFiles = [];
    public $oldFolders = [];
    public $renameTables = [];
    public $moduleStats = [];
    public $modCopyright;

    /**
     * Configurator constructor.
     */
    public function __construct()
    {
        $config = require \dirname(\dirname(__DIR__)) . '/config/config.php';

        $this->name = $config->name;
        $this->uploadFolders = $config->uploadFolders;
        $this->copyBlankFiles = $config->copyBlankFiles;
        $this->copyTestFolders = $config->copyTestFolders;
        $this->templateFolders = $config->templateFolders;
        $this->oldFiles = $config->oldFiles;
        $this->oldFolders = $config->oldFolders;
        $this->renameTables = $config->renameTables;
        $this->moduleStats = $config->moduleStats;
        $this->modCopyright = $config->modCopyright;

        $this->paths = require \dirname(\dirname(__DIR__)) . '/config/paths.php';
        $this->icons = require \dirname(\dirname(__DIR__)) . '/config/icons.php';
    }
}
