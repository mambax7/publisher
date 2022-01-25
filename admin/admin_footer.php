<?php declare(strict_types=1);
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @since
 * @author       XOOPS Development Team
 */

use Xmf\Module\Admin;
use XoopsModules\Publisher;

/** @var Publisher\Helper $helper */
$helper = Publisher\Helper::getInstance();

$pathIcon32 = Admin::iconUrl('', '32');

echo "<div class='adminfooter'>\n" . "  <div style='text-align: center;'>\n" . "    <a href='https://xoops.org' rel='external'><img src='{$pathIcon32}/xoopsmicrobutton.gif' alt='XOOPS' title='XOOPS'></a>\n" . "  </div>\n" . '  ' . _AM_MODULEADMIN_ADMIN_FOOTER . "\n" . '</div>';


global $xoTheme;

if (!isset($xoTheme)) {
    include_once $GLOBALS['xoops']->path('/class/theme.php');
    $GLOBALS['xoTheme'] = new \xos_opal_Theme();
    $xoTheme            = $GLOBALS['xoTheme'];
}

$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript('browse.php?Frameworks/jquery/plugins/jquery.tablesorter.js');
$xoTheme->addScript($helper->url('assets/js/tablesorter/js/jquery.tablesorter.js'));
$xoTheme->addScript($helper->url('assets/js/tablesorter/js/jquery.tablesorter.widgets.js'));
$xoTheme->addScript($helper->url('assets/js/tablesorter/js/extras/jquery.tablesorter.pager.min.js'));
$xoTheme->addScript($helper->url('assets/js/tablesorter/js/widgets/widget-pager.min.js'));

$xoTheme->addScript($helper->url('assets/js/tablesorter/functions.js'));


xoops_cp_footer();
