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

use XoopsModules\Publisher\Helper;
use XoopsModules\Publisher\Item;
use XoopsModules\Publisher\ItemHandler;

/** @var ItemHandler $itemHandler */
require_once __DIR__ . '/common.php';

/**
 * @param array        $queryArray
 * @param              $andor
 * @param              $limit
 * @param              $offset
 * @param              $userid
 * @param array        $categories
 * @param int          $sortby
 * @param string       $searchin
 * @param string       $extra
 *
 * @return array
 */
function publisher_search($queryArray, $andor, $limit, $offset, $userid, $categories = [], $sortby = 0, $searchin = '', $extra = '')
{
    $helper        = Helper::getInstance();
    $ret           = $item = [];
    $hightlightKey = '';

    if (is_array($queryArray)) {
        if (0 === count($queryArray)) {
            $hightlightKey = '';
        } else {
            $keywords      = implode('+', $queryArray);
            $hightlightKey = '&amp;keywords=' . $keywords;
        }
    }

    $itemHandler      = $helper->getHandler('Item');
    $itemsObjs        = $itemHandler->getItemsFromSearch($queryArray, $andor, $limit, $offset, $userid, $categories, $sortby, $searchin, $extra);
    $withCategoryPath = $helper->getConfig('search_cat_path');
    //xoops_load("xoopslocal");
    $usersIds = [];
    /** @var Item $obj */
    if (0 !== count($itemsObjs)) {
        foreach ($itemsObjs as $obj) {
            $item['image'] = 'assets/images/item_icon.gif';
            $item['link']  = $obj->getItemUrl();
            $item['link']  .= (!empty($hightlightKey) && (false === mb_strpos($item['link'], '.php?'))) ? '?' . ltrim($hightlightKey, '&amp;') : $hightlightKey;
            if ($withCategoryPath) {
                $item['title'] = $obj->getCategoryPath(false) . ' > ' . $obj->getTitle();
            } else {
                $item['title'] = $obj->getTitle();
            }
            $item['time'] = $obj->getVar('datesub'); //must go has unix timestamp
            $item['uid']  = $obj->uid();

            // Get item's flags for sanitization
            $dohtml = $obj->getVar('dohtml');
            $dosmiley = $obj->getVar('dosmiley');
            $doxcode = $obj->getVar('doxcode');
            $doimage = $obj->getVar('doimage');
            $dobr = $obj->getVar('dobr');

            //"Fulltext search/highlight
            $sanitizedText = '';
            $queryArray    = is_array($queryArray) ? $queryArray : [$queryArray];

            if ('' != $queryArray[0] && count($queryArray) > 0) {
                // Determine the base text for snippet generation
                if ($dohtml) {
                    $base_text_for_snippet = $obj->getVar('body', 'N'); // Get raw body
                } else {
                    $base_text_for_snippet = $obj->getVar('body', 'S'); // Get htmlspecialchars'd body
                }
                $textLower = \mb_strtolower($base_text_for_snippet);

                foreach ($queryArray as $query) {
                    $pos    = \mb_stripos($textLower, \mb_strtolower($query));
                    $start  = max($pos - 100, 0);
                    $length = \mb_strlen($query) + 200;

                    // Take snippet from the correct base text (raw or escaped)
                    $snippet_text = xoops_substr($base_text_for_snippet, $start, $length, ' [...]');
                    $context      = $obj->highlight($snippet_text, $query); // Highlight applies to the snippet
                    $sanitizedText .= '<p>[...] ' . $context . '</p>';
                }
            }
            //End of highlight

            // Sanitize the final snippet for display
            $myts = \MyTextSanitizer::getInstance();
            if ($dohtml) {
                // Pass the generated snippet (with highlighting) through displayTarea
                $item['text'] = $myts->displayTarea($sanitizedText, $dohtml, $dosmiley, $doxcode, $doimage, $dobr);
            } else {
                // If not dohtml, the text was already based on htmlspecialchars'd content.
                // Highlighting span is considered safe.
                $item['text'] = $sanitizedText;
            }

            $item['author']    = $obj->author_alias();
            $item['datesub']   = $obj->getDatesub($helper->getConfig('format_date'));
            $objUid            = $obj->uid();
            $usersIds[$objUid] = $objUid;
            $ret[]             = $item;
            unset($item, $sanitizedText);
        }
    }
    xoops_load('XoopsUserUtility');
    $usersNames = \XoopsUserUtility::getUnameFromIds($usersIds, $helper->getConfig('format_realname'), true);
    foreach ($ret as $key => $item) {
        if ('' == $item['author']) {
            $ret[$key]['author'] = $usersNames[$item['uid']] ?? '';
        }
    }
    unset($usersNames, $usersIds);

    return $ret;
}
