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
 * @author          The SmartFactory <www.smartfactory.ca>
 */

use XoopsModules\Publisher\Helper;
use XoopsModules\Publisher\Seo;

//require_once __DIR__ . '/seo_functions.php';

/**
 * @param $category
 * @param $itemId
 *
 * @return mixed
 */
function publisher_notify_iteminfo($category, $itemId)
{
    if ('global' === $category) {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    global $xoopsModule;
    $helper = Helper::getInstance();

    if ('category' === $category) {
        // Assume we have a valid category id
        $sql          = 'SELECT name, short_url FROM ' . $GLOBALS['xoopsDB']->prefix($helper->getDirname() . '_categories') . ' WHERE categoryid  = ' . $itemId;
        $result       = $GLOBALS['xoopsDB']->query($sql); // TODO: error check
        $resultArray  = $GLOBALS['xoopsDB']->fetchArray($result);
        $item['name'] = $resultArray['name'];
        $item['url']  = Seo::generateUrl('category', $itemId, $resultArray['short_url']);

        return $item;
    }

    if ('item' === $category) {
        // Assume we have a valid story id
        $sql          = 'SELECT title, short_url FROM ' . $GLOBALS['xoopsDB']->prefix($helper->getDirname() . '_items') . ' WHERE itemid = ' . $itemId;
        $result       = $GLOBALS['xoopsDB']->query($sql); // TODO: error check
        $resultArray  = $GLOBALS['xoopsDB']->fetchArray($result);
        $item['name'] = $resultArray['title'];
        $item['url']  = Seo::generateUrl('item', $itemId, $resultArray['short_url']);

        return $item;
    }

    return null;
}
