<?php declare(strict_types=1);
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         https://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

use Xmf\Request;
use XoopsModules\Publisher\Helper;
use XoopsModules\Publisher\Item;

/** @var Helper $helper */
/** @var Item $itemObj */
require_once \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/include/common.php';

$helper = Helper::getInstance();

$com_itemid = Request::getInt('com_itemid', 0, 'GET');
if ($com_itemid > 0) {
    $itemObj       = $helper->getHandler('Item')
                            ->get($com_itemid);
    $com_replytext = _POSTEDBY . '&nbsp;<strong>' . $itemObj->getLinkedPosterName() . '</strong>&nbsp;' . _DATE . '&nbsp;<strong>' . $itemObj->dateSub() . '</strong><br><br>' . $itemObj->summary();
    $bodytext      = $itemObj->body();
    if ('' != $bodytext) {
        $com_replytext .= '<br><br>' . $bodytext;
    }
    $com_replytitle = $itemObj->getTitle();
    require_once \dirname(__DIR__, 2) . '/include/comment_new.php';
}
