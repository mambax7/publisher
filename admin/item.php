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

use Xmf\Request;
use XoopsModules\Publisher\Constants;
use XoopsModules\Publisher\Helper;
use XoopsModules\Publisher\Item;
use XoopsModules\Publisher\Utility;
/** @var Helper $helper */

require_once __DIR__ . '/admin_header.php';

// all post requests should have a valid token
if ('POST' === Request::getMethod() && !$GLOBALS['xoopsSecurity']->check()) {
    redirect_header('item.php', 2, _CO_PUBLISHER_BAD_TOKEN);
}

global $xoopsDB, $xoopsConfig, $xoTheme;

//It recovered the value of argument op in URL$
$op    = \Xmf\Request::getString('op', 'list');
$order = \Xmf\Request::getString('order', 'desc');
$sort  = \Xmf\Request::getString('sort', '');

$moduleDirName = \basename(\dirname(__DIR__));
$xoTheme->addStylesheet($helper->url('assets/js/tablesorter/css/theme.blue.css'));
$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/items/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/items/";



$itemId = Request::getInt('itemid', Request::getInt('itemid', 0, 'POST'), 'GET');
$op     = ($itemId > 0 || Request::getString('editor', '', 'POST')) ? 'mod' : '';
//$op     = Request::getString('op', $op, 'GET');

$op = Request::getString('op', Request::getString('op', $op, 'POST'), 'GET');

$op = Request::getString('additem', '', 'POST') ? 'additem' : (Request::getString('del', '', 'POST') ? 'del' : $op);

// Where shall we start ?
$submittedstartitem = Request::getInt('submittedstartitem', Request::getInt('submittedstartitem', 0, 'GET'), 'POST');
$publishedstartitem = Request::getInt('publishedstartitem', Request::getInt('publishedstartitem', 0, 'GET'), 'POST');
$expiredstartitem   = Request::getInt('publishedstartitem', Request::getInt('expiredstartitem', 0, 'GET'), 'POST');
$offlinestartitem   = Request::getInt('offlinestartitem', Request::getInt('offlinestartitem', 0, 'GET'), 'POST');
$rejectedstartitem  = Request::getInt('rejectedstartitem', Request::getInt('submittedstartitem', 0, 'GET'), 'POST');

switch ($op) {
    case 'clone':
        if (0 == $itemId) {
            $totalcategories = $helper->getHandler('Category')
                                      ->getCategoriesCount(-1);
            if (0 == $totalcategories) {
                redirect_header('category.php?op=mod', 3, _AM_PUBLISHER_NEED_CATEGORY_ITEM);
            }
        }
        Utility::cpHeader();
        publisher_editItem(true, $itemId, true);
        break;
    case 'mod':
        if (0 == $itemId) {
            $totalcategories = $helper->getHandler('Category')
                                      ->getCategoriesCount(-1);
            if (0 == $totalcategories) {
                redirect_header('category.php?op=mod', 3, _AM_PUBLISHER_NEED_CATEGORY_ITEM);
            }
        }

        Utility::cpHeader();
        publisher_editItem(true, $itemId);
        break;
    case 'additem':
        $redirectMsg = $errorMsg = '';
        // Creating the item object
        /** @var Item $itemObj */
        if (0 != $itemId) {
            $itemObj = $helper->getHandler('Item')
                              ->get($itemId);
        } else {
            $itemObj = $helper->getHandler('Item')
                              ->create();
        }

        $itemObj->setVarsFromRequest();

        $oldStatus = $itemObj->status();
        $newStatus = Request::getInt('status', Constants::PUBLISHER_STATUS_PUBLISHED); //_PUBLISHER_STATUS_NOTSET;

        switch ($newStatus) {
            case Constants::PUBLISHER_STATUS_SUBMITTED:
                $errorMsg = _AM_PUBLISHER_ITEMNOTCREATED;
                if (Constants::PUBLISHER_STATUS_NOTSET == $oldStatus) {
                    $errorMsg = _AM_PUBLISHER_ITEMNOTUPDATED;
                }
                $redirectMsg = _AM_PUBLISHER_ITEM_RECEIVED_NEED_APPROVAL;
                break;
            case Constants::PUBLISHER_STATUS_PUBLISHED:
                if ((Constants::PUBLISHER_STATUS_NOTSET == $oldStatus) || (Constants::PUBLISHER_STATUS_SUBMITTED == $oldStatus)) {
                    $redirectMsg = _AM_PUBLISHER_SUBMITTED_APPROVE_SUCCESS;
                    $notifToDo   = [Constants::PUBLISHER_NOTIFY_ITEM_PUBLISHED];
                } else {
                    $redirectMsg = _AM_PUBLISHER_PUBLISHED_MOD_SUCCESS;
                }
                $errorMsg = _AM_PUBLISHER_ITEMNOTUPDATED;
                break;
            case Constants::PUBLISHER_STATUS_OFFLINE:
                $redirectMsg = _AM_PUBLISHER_OFFLINE_MOD_SUCCESS;
                if (Constants::PUBLISHER_STATUS_NOTSET == $oldStatus) {
                    $redirectMsg = _AM_PUBLISHER_OFFLINE_CREATED_SUCCESS;
                }
                $errorMsg = _AM_PUBLISHER_ITEMNOTUPDATED;
                break;
            case Constants::PUBLISHER_STATUS_REJECTED:
                $errorMsg = _AM_PUBLISHER_ITEMNOTCREATED;
                if (Constants::PUBLISHER_STATUS_NOTSET == $oldStatus) {
                    $errorMsg = _AM_PUBLISHER_ITEMNOTUPDATED;
                }
                $redirectMsg = _AM_PUBLISHER_ITEM_REJECTED;
                break;
        }
        $itemObj->setVar('status', $newStatus);

        // Storing the item
        if (!$itemObj->store()) {
            redirect_header('<script>javascript:history.go(-1)</script>', 3, $errorMsg . Utility::formatErrors($itemObj->getErrors()));
        }

        // attach file if any
        if (($item_upload_file = Request::getArray('item_upload_file', '', 'FILES')) && '' !== $item_upload_file['name']) {
            $file_upload_result = Utility::uploadFile(false, false, $itemObj);
            if (true !== $file_upload_result) {
                redirect_header('<script>javascript:history.go(-1)</script>', 3, $file_upload_result);
            }
        }

        // Send notifications
        if ($notifToDo) {
            $itemObj->sendNotifications($notifToDo);
        }

        redirect_header('item.php', 2, $redirectMsg);

        break;
    case 'del':
        $itemObj = $helper->getHandler('Item')
                          ->get($itemId);
        $confirm = Request::getInt('confirm', 0, 'POST');

        if ($confirm) {
            if (!$helper->getHandler('Item')
                        ->delete($itemObj)) {
                redirect_header('item.php', 2, _AM_PUBLISHER_ITEM_DELETE_ERROR . Utility::formatErrors($itemObj->getErrors()));
            }
            redirect_header('item.php', 2, sprintf(_AM_PUBLISHER_ITEMISDELETED, $itemObj->getTitle()));
        } else {
            xoops_cp_header();
            xoops_confirm(['op' => 'del', 'itemid' => $itemObj->itemid(), 'confirm' => 1, 'name' => $itemObj->getTitle()], 'item.php', _AM_PUBLISHER_DELETETHISITEM . " <br>'" . $itemObj->getTitle() . "'. <br> <br>", _AM_PUBLISHER_DELETE);
            xoops_cp_footer();
        }
        exit();
    case 'default':
    default:
        Utility::cpHeader();
        //publisher_adminMenu(2, _AM_PUBLISHER_ITEMS);
        xoops_load('XoopsPageNav');

        echo "<br>\n";
        echo '<form><div style="margin-bottom: 12px;">';
        echo "<input type='button' name='button' onclick=\"location='item.php?op=mod'\" value='" . _AM_PUBLISHER_CREATEITEM . "'>&nbsp;&nbsp;";
        echo '</div></form>';

        $orderBy   = 'datesub';
        $ascOrDesc = 'DESC';

        // Display Submited articles
        Utility::openCollapsableBar('submiteditemstable', 'submiteditemsicon', _AM_PUBLISHER_SUBMISSIONSMNGMT, _AM_PUBLISHER_SUBMITTED_EXP);

        // Get the total number of submitted ITEM
        $totalitems = $helper->getHandler('Item')
                             ->getItemsCount(-1, [Constants::PUBLISHER_STATUS_SUBMITTED]);

        $itemsObj = $helper->getHandler('Item')
                           ->getAllSubmitted($helper->getConfig('idxcat_perpage'), $submittedstartitem, -1, $orderBy, $ascOrDesc);

        $totalItemsOnPage = count($itemsObj);

    //=========================================================================

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo '<tr>';
        echo "<th width='40' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEMID . '</strong></td>';
        echo "<th width='20%' class='bg3' align='left'><strong>" . _AM_PUBLISHER_ITEMCATEGORYNAME . '</strong></td>';
        echo "<th class='bg3' align='left'><strong>" . _AM_PUBLISHER_TITLE . '</strong></td>';
        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_EXPIRE . '</strong></td>';
        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_CREATED . '</strong></td>';
        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_AUTHOR . '</strong></td>';
        echo "<th width='80' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ACTION . '</strong></td>';
        echo '</tr>';
        if ($totalitems > 0) {
            for ($i = 0; $i < $totalItemsOnPage; ++$i) {
                $categoryObj = $itemsObj[$i]->getCategory();

                $approve = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['moderate'] . '</a>&nbsp;';
                $clone   = '';
                $delete  = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['delete'] . '</a>';
                $modify  = '';

                echo '<tr>';
                echo "<td class='head' align='center'>" . $itemsObj[$i]->itemid() . '</td>';
                echo "<td class='even' align='left'>" . $categoryObj->getCategoryLink() . '</td>';
                echo "<td class='even' align='left'><a href='" . PUBLISHER_URL . '/item.php?itemid=' . $itemsObj[$i]->itemid() . "'>" . $itemsObj[$i]->getTitle() . '</a></td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->getDateExpire() . '</td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->getDatesub() . '</td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->getWho() . '</td>';
                echo "<td class='even' align='center'> $approve $clone $modify $delete </td>";
                echo '</tr>';
            }
        } else {
            $itemId = 0;
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOITEMS_SUBMITTED . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        echo "<br>\n";

        $pagenav = new \XoopsPageNav($totalitems, $helper->getConfig('idxcat_perpage'), $submittedstartitem, 'submittedstartitem');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        Utility::closeCollapsableBar('submiteditemstable', 'submiteditemsicon');

        // Display Published articles
        Utility::openCollapsableBar('item_publisheditemstable', 'item_publisheditemsicon', _AM_PUBLISHER_PUBLISHEDITEMS, _AM_PUBLISHER_PUBLISHED_DSC);

//        // Get the total number of published ITEM
        $totalitems = $helper->getHandler('Item')->getItemsCount(-1, [Constants::PUBLISHER_STATUS_PUBLISHED]);
//        $itemsObj = $helper->getHandler('Item')->getAllPublished($helper->getConfig('idxcat_perpage'), $publishedstartitem, -1, $orderBy, $ascOrDesc, '', true, 'none', false);
//
//        $totalItemsOnPage = count($itemsObj);
//
//        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
//        echo '<tr>';
//        echo "<th width='40' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEMID . '</strong></td>';
//        echo "<th width='20%' class='bg3' align='left'><strong>" . _AM_PUBLISHER_ITEMCATEGORYNAME . '</strong></td>';
//        echo "<th class='bg3' align='left'><strong>" . _AM_PUBLISHER_TITLE . '</strong></td>';
//        echo "<th width='30' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEM_VIEWS . '</strong></td>';
//        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_EXPIRE . '</strong></td>';
//        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_CREATED . '</strong></td>';
//        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_AUTHOR . '</strong></td>';
//        echo "<th width='80' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ACTION . '</strong></td>';
//        echo '</tr>';
//        if ($totalitems > 0) {
//            for ($i = 0; $i < $totalItemsOnPage; ++$i) {
//                $categoryObj = $itemsObj[$i]->getCategory();
//
//                $modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['edit'] . '</a>';
//                $delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['delete'] . '</a>';
//                $clone  = "<a href='item.php?op=clone&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['clone'] . '</a>';
//
//                echo '<tr>';
//                echo "<td class='head' align='center'>" . $itemsObj[$i]->itemid() . '</td>';
//                echo "<td class='even' align='left'>" . $categoryObj->getCategoryLink() . '</td>';
//                echo "<td class='even' align='left'>" . $itemsObj[$i]->getItemLink() . '</td>';
//                echo "<td class='even' align='center'>" . $itemsObj[$i]->counter() . '</td>';
//                echo "<td class='even' align='center'>" . $itemsObj[$i]->getDateExpire() . '</td>';
//                echo "<td class='even' align='center'>" . $itemsObj[$i]->getDatesub() . '</td>';
//                echo "<td class='even' align='center'>" . $itemsObj[$i]->getWho() . '</td>';
//                echo "<td class='even' align='center'> $modify $delete $clone</td>";
//                echo '</tr>';
//            }
//        } else {
//            $itemId = 0;
//            echo '<tr>';
//            echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOITEMS . '</td>';
//            echo '</tr>';
//        }
//        echo "</table>\n";
//        echo "<br>\n";
//
//        $pagenav = new \XoopsPageNav($totalitems, $helper->getConfig('idxcat_perpage'), $publishedstartitem, 'publishedstartitem');
//        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

    //=========================================================================

    $xoTheme->addStylesheet($helper->url('assets/js/tablesorter/css/theme.blue.css'));


    $start                = \Xmf\Request::getInt('start', 0);
    $itemsPaginationLimit = $helper->getConfig('userpager');
    /** @var \XoopsPersistableObjectHandler $categoriesHandler */
    $categoriesHandler = $helper->getHandler('Category');

    $criteria = new \CriteriaCompo();
    $criteria->setSort('itemid ASC, title');
    $criteria->setOrder('ASC');
    $criteria->setLimit($itemsPaginationLimit);
    $criteria->setStart($start);

    $itemsHandler = $helper->getHandler('Item');
    $itemsTempRows  = $itemsHandler->getCount();
    $itemsTempArray = $itemsHandler->getAll($criteria);

        $itemsObj = $helper->getHandler('Item')->getAllPublished($helper->getConfig('idxcat_perpage'), $publishedstartitem, -1, $orderBy, $ascOrDesc, '', true, 'none', false);
        xoops_load('XoopsPageNav');

        $pagenav = new \XoopsPageNav($itemsTempRows, $itemsPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
        $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
    }

    $GLOBALS['xoopsTpl']->assign('itemsRows', $itemsTempRows);
    $itemsArray = [];

    $criteria = new \CriteriaCompo();

    //$criteria->setOrder('DESC');
    $criteria->setSort($sort);
    $criteria->setOrder($order);
    $criteria->setLimit($itemsPaginationLimit);
    $criteria->setStart($start);

    $itemsCount     = $itemsHandler->getCount($criteria);
    $itemsTempArray = $itemsHandler->getAll($criteria);

    //    for ($i = 0; $i < $fieldsCount; ++$i) {
    if ($itemsCount > 0) {
        foreach (array_keys($itemsTempArray) as $i) {
            //        $field = explode(':', $fields[$i]);

            $GLOBALS['xoopsTpl']->assign('selectoritemid', AM_PUBLISHER_ITEMS_ITEMID);
            $itemsArray['itemid'] = $itemsTempArray[$i]->getVar('itemid');

            $GLOBALS['xoopsTpl']->assign('selectorcategoryid', AM_PUBLISHER_ITEMS_CATEGORYID);
            $itemsArray['categoryid'] = $categoriesHandler->get($itemsTempArray[$i]->getVar('categoryid'))
                                                          ->getVar('name');//

            //            $selectortitle = $utility::selectSorting(AM_PUBLISHER_ITEMS_TITLE, 'title');
            //$GLOBALS['xoopsTpl']->assign('selectortitle', $selectortitle);
            $GLOBALS['xoopsTpl']->assign('selectortitle', AM_PUBLISHER_ITEMS_TITLE);
            $itemsArray['title'] = $itemsTempArray[$i]->getVar('title');
            $itemsArray['title'] = $utility::truncateHtml($itemsArray['title'], $helper->getConfig('item_title_size'));

//            $GLOBALS['xoopsTpl']->assign('selectorsubtitle', AM_PUBLISHER_ITEMS_SUBTITLE);
//            $itemsArray['subtitle'] = $itemsTempArray[$i]->getVar('subtitle');

            $GLOBALS['xoopsTpl']->assign('selectorsummary', AM_PUBLISHER_ITEMS_SUMMARY);
            $itemsArray['summary'] = $itemsTempArray[$i]->getVar('summary');
            $itemsArray['summary'] = $utility::truncateHtml($itemsArray['summary'], $helper->getConfig('item_title_size'));

            $GLOBALS['xoopsTpl']->assign('selectorbody', AM_PUBLISHER_ITEMS_BODY);
            $itemsArray['body'] = $itemsTempArray[$i]->getVar('body');
            $itemsArray['body'] = $utility::truncateHtml($itemsArray['body'], $helper->getConfig('item_title_size'));//

            //            $selectoruid = $utility::selectSorting(AM_PUBLISHER_ITEMS_UID, 'uid');
            //$GLOBALS['xoopsTpl']->assign('selectoruid', $selectoruid);
            $GLOBALS['xoopsTpl']->assign('selectoruid', AM_PUBLISHER_ITEMS_UID);
            $itemsArray['uid'] = strip_tags((\XoopsUser::getUnameFromId($itemsTempArray[$i]->getVar('uid'))) ?? '');

            $GLOBALS['xoopsTpl']->assign('selectorauthor_alias', AM_PUBLISHER_ITEMS_AUTHOR_ALIAS);
            $itemsArray['author_alias'] = $itemsTempArray[$i]->getVar('author_alias');

            $GLOBALS['xoopsTpl']->assign('selectordatesub', AM_PUBLISHER_ITEMS_DATESUB);
            $itemsArray['datesub'] = formatTimestamp($itemsTempArray[$i]->getVar('datesub'), 's');

            $GLOBALS['xoopsTpl']->assign('selectorstatus', AM_PUBLISHER_ITEMS_STATUS);
            $itemsArray['status'] = $itemsTempArray[$i]->getVar('status');

            $GLOBALS['xoopsTpl']->assign('selectorimage', AM_PUBLISHER_ITEMS_IMAGE);
            $itemsArray['image'] = $itemsTempArray[$i]->getVar('image');

            $GLOBALS['xoopsTpl']->assign('selectorimages', AM_PUBLISHER_ITEMS_IMAGES);
            $itemsArray['images'] = "<img src='" . $uploadUrl . $itemsTempArray[$i]->getVar('images') . "' name='" . 'name' . "' id=" . 'id' . " alt='' style='max-width:100px'>";

            $GLOBALS['xoopsTpl']->assign('selectorcounter', AM_PUBLISHER_ITEMS_COUNTER);
            $itemsArray['counter'] = $itemsTempArray[$i]->getVar('counter');//

            //            $selectorrating = $utility::selectSorting(AM_PUBLISHER_ITEMS_RATING, 'rating');
            //$GLOBALS['xoopsTpl']->assign('selectorrating', $selectorrating);
            $GLOBALS['xoopsTpl']->assign('selectorrating', AM_PUBLISHER_ITEMS_RATING);
            $itemsArray['rating'] = $itemsTempArray[$i]->getVar('rating');

            $GLOBALS['xoopsTpl']->assign('selectorvotes', AM_PUBLISHER_ITEMS_VOTES);
            $itemsArray['votes'] = $itemsTempArray[$i]->getVar('votes');//

            //            $selectorweight = $utility::selectSorting(AM_PUBLISHER_ITEMS_WEIGHT, 'weight');
            //$GLOBALS['xoopsTpl']->assign('selectorweight', $selectorweight);
            $GLOBALS['xoopsTpl']->assign('selectorweight', AM_PUBLISHER_ITEMS_WEIGHT);
            $itemsArray['weight'] = $itemsTempArray[$i]->getVar('weight');

            $GLOBALS['xoopsTpl']->assign('selectordohtml', AM_PUBLISHER_ITEMS_DOHTML);
            $itemsArray['dohtml'] = $itemsTempArray[$i]->getVar('dohtml');

            $GLOBALS['xoopsTpl']->assign('selectordosmiley', AM_PUBLISHER_ITEMS_DOSMILEY);
            $itemsArray['dosmiley'] = $itemsTempArray[$i]->getVar('dosmiley');

            $GLOBALS['xoopsTpl']->assign('selectordoxcode', AM_PUBLISHER_ITEMS_DOXCODE);
            $itemsArray['doxcode'] = $itemsTempArray[$i]->getVar('doxcode');

            $GLOBALS['xoopsTpl']->assign('selectordoimage', AM_PUBLISHER_ITEMS_DOIMAGE);
            $itemsArray['doimage'] = $itemsTempArray[$i]->getVar('doimage');

            $GLOBALS['xoopsTpl']->assign('selectordobr', AM_PUBLISHER_ITEMS_DOBR);
            $itemsArray['dobr'] = $itemsTempArray[$i]->getVar('dobr');

            $GLOBALS['xoopsTpl']->assign('selectorcancomment', AM_PUBLISHER_ITEMS_CANCOMMENT);
            $itemsArray['cancomment'] = $itemsTempArray[$i]->getVar('cancomment');//

            //            $selectorcomments = $utility::selectSorting(AM_PUBLISHER_ITEMS_COMMENTS, 'comments');
            //$GLOBALS['xoopsTpl']->assign('selectorcomments', $selectorcomments);
            $GLOBALS['xoopsTpl']->assign('selectorcomments', AM_PUBLISHER_ITEMS_COMMENTS);
            $itemsArray['comments'] = $itemsTempArray[$i]->getVar('comments');

            $GLOBALS['xoopsTpl']->assign('selectornotifypub', AM_PUBLISHER_ITEMS_NOTIFYPUB);
            $itemsArray['notifypub'] = $itemsTempArray[$i]->getVar('notifypub');

            $GLOBALS['xoopsTpl']->assign('selectormeta_keywords', AM_PUBLISHER_ITEMS_META_KEYWORDS);
            $itemsArray['meta_keywords'] = strip_tags(($itemsTempArray[$i]->getVar('meta_keywords')) ?? '');

            $GLOBALS['xoopsTpl']->assign('selectormeta_description', AM_PUBLISHER_ITEMS_META_DESCRIPTION);
            $itemsArray['meta_description'] = strip_tags(($itemsTempArray[$i]->getVar('meta_description')) ?? '');

            $GLOBALS['xoopsTpl']->assign('selectorshort_url', AM_PUBLISHER_ITEMS_SHORT_URL);
            $itemsArray['short_url'] = $itemsTempArray[$i]->getVar('short_url');

            $GLOBALS['xoopsTpl']->assign('selectoritem_tag', AM_PUBLISHER_ITEMS_ITEM_TAG);
            $itemsArray['item_tag']    = strip_tags(($itemsTempArray[$i]->getVar('item_tag')) ?? '');
            $itemsArray['edit_delete'] = "<a href='item.php?op=mod&itemid=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='item.php?op=delete&itemid=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='item.php?op=clone&itemid=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

            $GLOBALS['xoopsTpl']->append_by_ref('itemsArrays', $itemsArray);
            unset($itemsArray);
            }
        unset($itemsTempArray);
        // Display Navigation
        if ($itemsCount > $itemsPaginationLimit) {
            xoops_load('XoopsPageNav');
            $pagenav = new \XoopsPageNav($itemsCount, $itemsPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
            $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
        }

        $GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

        echo $GLOBALS['xoopsTpl']->fetch(XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/publisher_admin_items.tpl');
    }
    //=========================================================================

        Utility::closeCollapsableBar('item_publisheditemstable', 'item_publisheditemsicon');

        // Display Offline articles
        Utility::openCollapsableBar('offlineitemstable', 'offlineitemsicon', _AM_PUBLISHER_ITEMS . ' ' . _CO_PUBLISHER_OFFLINE, _AM_PUBLISHER_OFFLINE_EXP);

        $totalitems = $helper->getHandler('Item')
                             ->getItemsCount(-1, [Constants::PUBLISHER_STATUS_OFFLINE]);

        $itemsObj = $helper->getHandler('Item')
                           ->getAllOffline($helper->getConfig('idxcat_perpage'), $offlinestartitem, -1, $orderBy, $ascOrDesc);

        $totalItemsOnPage = count($itemsObj);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo '<tr>';
        echo "<th width='40' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEMID . '</strong></td>';
        echo "<th width='20%' class='bg3' align='left'><strong>" . _AM_PUBLISHER_ITEMCATEGORYNAME . '</strong></td>';
        echo "<th class='bg3' align='left'><strong>" . _AM_PUBLISHER_TITLE . '</strong></td>';
        echo "<th width='30' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEM_VIEWS . '</strong></td>';
        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_EXPIRE . '</strong></td>';
        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_CREATED . '</strong></td>';
        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_AUTHOR . '</strong></td>';

        echo "<th width='80' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ACTION . '</strong></td>';
        echo '</tr>';
        if ($totalitems > 0) {
            for ($i = 0; $i < $totalItemsOnPage; ++$i) {
                $categoryObj = $itemsObj[$i]->getCategory();

                $modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['edit'] . '</a>';
                $delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['delete'] . '</a>';
                $clone  = "<a href='item.php?op=clone&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['clone'] . '</a>';

                echo '<tr>';
                echo "<td class='head' align='center'>" . $itemsObj[$i]->itemid() . '</td>';
                echo "<td class='even' align='left'>" . $categoryObj->getCategoryLink() . '</td>';
                echo "<td class='even' align='left'>" . $itemsObj[$i]->getItemLink() . '</td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->counter() . '</td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->getDateExpire() . '</td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->getDatesub() . '</td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->getWho() . '</td>';
                echo "<td class='even' align='center'>  $modify $delete $clone</td>";
                echo '</tr>';
            }
        } else {
            $itemId = 0;
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOITEMS_OFFLINE . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        echo "<br>\n";

        $pagenav = new \XoopsPageNav($totalitems, $helper->getConfig('idxcat_perpage'), $offlinestartitem, 'offlinestartitem');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        Utility::closeCollapsableBar('offlineitemstable', 'offlineitemsicon');

        // Display Rejected articles
        Utility::openCollapsableBar('Rejecteditemstable', 'rejecteditemsicon', _AM_PUBLISHER_REJECTED_ITEM, _AM_PUBLISHER_REJECTED_ITEM_EXP, _AM_PUBLISHER_SUBMITTED_EXP);

        // Get the total number of Rejected ITEM
        $totalitems = $helper->getHandler('Item')
                             ->getItemsCount(-1, [Constants::PUBLISHER_STATUS_REJECTED]);
        $itemsObj   = $helper->getHandler('Item')
                             ->getAllRejected($helper->getConfig('idxcat_perpage'), $rejectedstartitem, -1, $orderBy, $ascOrDesc);

        $totalItemsOnPage = count($itemsObj);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo '<tr>';
        echo "<th width='40' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEMID . '</strong></td>';
        echo "<th width='20%' class='bg3' align='left'><strong>" . _AM_PUBLISHER_ITEMCATEGORYNAME . '</strong></td>';
        echo "<th class='bg3' align='left'><strong>" . _AM_PUBLISHER_TITLE . '</strong></td>';
        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_EXPIRE . '</strong></td>';
        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_CREATED . '</strong></td>';
        echo "<th width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_AUTHOR . '</strong></td>';
        echo "<th width='80' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ACTION . '</strong></td>';
        echo '</tr>';

        if ($totalitems > 0) {
            for ($i = 0; $i < $totalItemsOnPage; ++$i) {
                $categoryObj = $itemsObj[$i]->getCategory();

                $modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['edit'] . '</a>';
                $delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['delete'] . '</a>';
                $clone  = "<a href='item.php?op=clone&itemid=" . $itemsObj[$i]->itemid() . "'>" . $icons['clone'] . '</a>';

                echo '<tr>';
                echo "<td class='head' align='center'>" . $itemsObj[$i]->itemid() . '</td>';
                echo "<td class='even' align='left'>" . $categoryObj->getCategoryLink() . '</td>';
                echo "<td class='even' align='left'>" . $itemsObj[$i]->getItemLink() . '</td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->getDateExpire() . '</td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->getDatesub() . '</td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->getWho() . '</td>';
                echo "<td class='even' align='center'> $modify $delete $clone</td>";
                echo '</tr>';
            }
        } else {
            $itemId = 0;
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOITEMS_REJECTED . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        echo "<br>\n";

        $pagenav = new \XoopsPageNav($totalitems, $helper->getConfig('idxcat_perpage'), $rejectedstartitem, 'rejectedstartitem');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        Utility::closeCollapsableBar('Rejecteditemstable', 'rejecteditemsicon');
        break;
}
require_once __DIR__ . '/admin_footer.php';

/**
 * @param bool $showmenu
 * @param int  $itemId
 * @param bool $clone
 */
function publisher_editItem($showmenu = false, $itemId = 0, $clone = false): void
{
    $helper = Helper::getInstance();
    global $publisherCurrentPage;

    xoops_load('XoopsFormLoader');

    $formTpl = new \XoopsTpl();
    //publisher_submit.html

    // if there is a parameter, and the id exists, retrieve data: we're editing a item

    if (0 !== $itemId) {
        // Creating the ITEM object
        /** @var Item $itemObj */
        $itemObj = $helper->getHandler('Item')
                          ->get($itemId);

        if (null === $itemObj) {
            redirect_header('item.php', 1, _AM_PUBLISHER_NOITEMSELECTED);
        }

        if ($clone) {
            $itemObj->setNew();
            $itemObj->setVar('itemid', 0);
            $itemObj->setVar('status', Constants::PUBLISHER_STATUS_NOTSET);
            $itemObj->setVar('datesub', time());
        }

        switch ($itemObj->getVar('status')) {
            case Constants::PUBLISHER_STATUS_SUBMITTED:
                $breadcrumbAction1 = _CO_PUBLISHER_SUBMITTED;
                $breadcrumbAction2 = _AM_PUBLISHER_APPROVING;
                $pageTitle         = _AM_PUBLISHER_SUBMITTED_TITLE;
                $pageInfo          = _AM_PUBLISHER_SUBMITTED_INFO;
                $buttonCaption     = _AM_PUBLISHER_APPROVE;
                $newStatus         = Constants::PUBLISHER_STATUS_PUBLISHED;
                break;
            case Constants::PUBLISHER_STATUS_PUBLISHED:
                $breadcrumbAction1 = _CO_PUBLISHER_PUBLISHED;
                $breadcrumbAction2 = _AM_PUBLISHER_EDITING;
                $pageTitle         = _AM_PUBLISHER_PUBLISHEDEDITING;
                $pageInfo          = _AM_PUBLISHER_PUBLISHEDEDITING_INFO;
                $buttonCaption     = _AM_PUBLISHER_MODIFY;
                $newStatus         = Constants::PUBLISHER_STATUS_PUBLISHED;
                break;
            case Constants::PUBLISHER_STATUS_OFFLINE:
                $breadcrumbAction1 = _CO_PUBLISHER_OFFLINE;
                $breadcrumbAction2 = _AM_PUBLISHER_EDITING;
                $pageTitle         = _AM_PUBLISHER_OFFLINEEDITING;
                $pageInfo          = _AM_PUBLISHER_OFFLINEEDITING_INFO;
                $buttonCaption     = _AM_PUBLISHER_MODIFY;
                $newStatus         = Constants::PUBLISHER_STATUS_OFFLINE;
                break;
            case Constants::PUBLISHER_STATUS_REJECTED:
                $breadcrumbAction1 = _CO_PUBLISHER_REJECTED;
                $breadcrumbAction2 = _AM_PUBLISHER_REJECTED;
                $pageTitle         = _AM_PUBLISHER_REJECTED_EDIT;
                $pageInfo          = _AM_PUBLISHER_REJECTED_EDIT_INFO;
                $buttonCaption     = _AM_PUBLISHER_MODIFY;
                $newStatus         = Constants::PUBLISHER_STATUS_REJECTED;
                break;
            case Constants::PUBLISHER_STATUS_NOTSET: // Then it's a clone...
                $breadcrumbAction1 = _AM_PUBLISHER_ITEMS;
                $breadcrumbAction2 = _AM_PUBLISHER_CLONE_NEW;
                $buttonCaption     = _AM_PUBLISHER_CREATE;
                $newStatus         = Constants::PUBLISHER_STATUS_PUBLISHED;
                $pageTitle         = _AM_PUBLISHER_ITEM_DUPLICATING;
                $pageInfo          = _AM_PUBLISHER_ITEM_DUPLICATING_DSC;
                break;
            case 'default':
            default:
                $breadcrumbAction1 = _AM_PUBLISHER_ITEMS;
                $breadcrumbAction2 = _AM_PUBLISHER_EDITING;
                $pageTitle         = _AM_PUBLISHER_PUBLISHEDEDITING;
                $pageInfo          = _AM_PUBLISHER_PUBLISHEDEDITING_INFO;
                $buttonCaption     = _AM_PUBLISHER_MODIFY;
                $newStatus         = Constants::PUBLISHER_STATUS_PUBLISHED;
                break;
        }

        $categoryObj = $itemObj->getCategory();

        echo "<br>\n";
        Utility::openCollapsableBar('edititemtable', 'edititemicon', $pageTitle, $pageInfo);

        if ($clone) {
            echo '<form><div style="margin-bottom: 10px;">';
            echo "<input type='button' name='button' onclick=\"location='item.php?op=clone&itemid=" . $itemObj->getVar('itemid') . "'\" value='" . _AM_PUBLISHER_CLONE_ITEM . "'>&nbsp;&nbsp;";
            echo '</div></form>';
        }
    } else {
        // there's no parameter, so we're adding an item
        /** @var Item $itemObj */
        $itemObj = $helper->getHandler('Item')
                          ->create();
        $itemObj->setVarsFromRequest();

        $categoryObj       = $helper->getHandler('Category')
                                    ->create();
        $breadcrumbAction1 = _AM_PUBLISHER_ITEMS;
        $breadcrumbAction2 = _AM_PUBLISHER_CREATINGNEW;
        $buttonCaption     = _AM_PUBLISHER_CREATE;
        $newStatus         = Constants::PUBLISHER_STATUS_PUBLISHED;

        $categoryObj->setVar('categoryid', Request::getInt('categoryid', 0, 'GET'));

        Utility::openCollapsableBar('createitemtable', 'createitemicon', _AM_PUBLISHER_ITEM_CREATING, _AM_PUBLISHER_ITEM_CREATING_DSC);
    }

    $sform = $itemObj->getForm(_AM_PUBLISHER_ITEMS);

    $sform->assign($formTpl);
    $formTpl->display('db:publisher_submit.tpl');

    Utility::closeCollapsableBar('edititemtable', 'edititemicon');

    Utility::openCollapsableBar('pagewraptable', 'pagewrapicon', _AM_PUBLISHER_PAGEWRAP, _AM_PUBLISHER_PAGEWRAPDSC);

    $dir = Utility::getUploadDir(true, 'content');

    if (!is_writable($dir)) {
        echo "<span style='color:#ff0000;'><h4>" . _AM_PUBLISHER_PERMERROR . '</h4></span>';
    }

    // Upload File
    echo "<form name='form_name2' id='form_name2' action='pw_upload_file.php' method='post' enctype='multipart/form-data'>";
    echo "<table cellspacing='1' width='100%' class='outer'>";
    echo "<tr><th colspan='2'>" . _AM_PUBLISHER_UPLOAD_FILE . '</th></tr>';
    echo "<tr valign='top' align='left'><td class='head'>" . _AM_PUBLISHER_SEARCH_PW . "</td><td class='even'><input type='file' name='fileupload' id='fileupload' size='30'></td></tr>";
    echo "<tr valign='top' align='left'><td class='head'><input type='hidden' name='MAX_FILE_SIZE' id='op' value='500000'></td><td class='even'><input type='submit' name='submit' value='" . _AM_PUBLISHER_UPLOAD . "'></td></tr>";
    echo "<input type='hidden' name='backto' value='$publisherCurrentPage'>";
    echo '</table>';
    echo '</form>';

    // Delete File
    $form = new \XoopsThemeForm(_CO_PUBLISHER_DELETEFILE, 'form_name', 'pw_delete_file.php');

    $pWrapSelect = new \XoopsFormSelect(Utility::getUploadDir(true, 'content'), 'address');
    $folder      = dir($dir);
    while (false !== ($file = $folder->read())) {
        if ('.' !== $file && '..' !== $file) {
            $pWrapSelect->addOption($file, $file);
        }
    }
    $folder->close();
    $form->addElement($pWrapSelect);

    $delfile = 'delfile';
    $form->addElement(new \XoopsFormHidden('op', $delfile));
    $submit = new \XoopsFormButton('', 'submit', _AM_PUBLISHER_BUTTON_DELETE, 'submit');
    $form->addElement($submit);

    $form->addElement(new \XoopsFormHidden('backto', $publisherCurrentPage));
    $form->display();

    Utility::closeCollapsableBar('pagewraptable', 'pagewrapicon');
}
