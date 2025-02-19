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
use XoopsModules\Publisher\Helper;
use XoopsModules\Publisher\Utility;

/** @var Helper $helper */
require_once __DIR__ . '/admin_header.php';

$op = Request::getCmd('op', Request::getCmd('op', '', 'POST'), 'GET');

$op = Request::getString('editor', '', 'POST') ? 'mod' : $op;
$op = Request::getString('addcategory', '', 'POST') ? 'addcategory' : $op;

// Where do we start ?
$startcategory = Request::getInt('startcategory', 0, 'GET');
$categoryid    = Request::getInt('categoryid', null);

switch ($op) {
    case 'del':
        $categoryObj = $helper->getHandler('Category')
                              ->get($categoryid);
        $confirm     = Request::getString('confirm', '', 'POST');
        $name        = Request::getString('name', '', 'POST');
        if ($confirm) {
            if (!$helper->getHandler('Category')
                        ->delete($categoryObj)) {
                redirect_header('category.php', 1, _AM_PUBLISHER_DELETE_CAT_ERROR);
            }
            redirect_header('category.php', 1, sprintf(_AM_PUBLISHER_COLISDELETED, $name));
        } else {
            xoops_cp_header();
            xoops_confirm(['op' => 'del', 'categoryid' => $categoryObj->categoryid(), 'confirm' => 1, 'name' => $categoryObj->name()], 'category.php', _AM_PUBLISHER_DELETECOL . " '" . $categoryObj->name() . "'. <br> <br>" . _AM_PUBLISHER_DELETE_CAT_CONFIRM, _AM_PUBLISHER_DELETE);
            xoops_cp_footer();
        }
        break;
    case 'mod':
        //Added by fx2024
        $numberSubcats = Request::getInt('nb_subcats', 0, 'POST');
        $numberSubcats += Request::getInt('nb_sub_yet', 4, 'POST');
        //end of fx2024 code

        Utility::cpHeader();
        Utility::editCategory(true, $categoryid, $numberSubcats);
        break;
    case 'addcategory':
        global $modify;

        $parentid = Request::getInt('parentid');
        if (0 != $categoryid) {
            $categoryObj = $helper->getHandler('Category')
                                  ->get($categoryid);
        } else {
            $categoryObj = $helper->getHandler('Category')
                                  ->create();
        }

        // Uploading the image, if any
        // Retrieve the filename to be uploaded
        $temp      = Request::getArray('image_file', '', 'FILES');
        $imageFile = $temp['name'];
        if ($imageFile) {
            //            $filename = Request::getArray('xoops_upload_file', array(), 'POST')[0];
            $temp2    = Request::getArray('xoops_upload_file', [], 'POST');
            $filename = $temp2[0];
            if ($filename) {
                // TODO : implement publisher mimetype management
                $maxSize          = $helper->getConfig('maximum_filesize');
                $maxImageWidth    = $helper->getConfig('maximum_image_width');
                $maxImageHeight   = $helper->getConfig('maximum_image_height');
                $allowedMimetypes = Utility::getAllowedImagesTypes();
                if (('' == $temp['tmp_name']) || !is_readable($temp['tmp_name'])) {
                    redirect_header('<script>javascript:history.go(-1)</script>', 2, _AM_PUBLISHER_FILEUPLOAD_ERROR);
                }

                xoops_load('XoopsMediaUploader');
                $uploader = new \XoopsMediaUploader(Utility::getImageDir('category'), $allowedMimetypes, $maxSize, $maxImageWidth, $maxImageHeight);
                if ($uploader->fetchMedia($filename) && $uploader->upload()) {
                    $categoryObj->setVar('image', $uploader->getSavedFileName());
                } else {
                    redirect_header('<script>javascript:history.go(-1)</script>', 2, _AM_PUBLISHER_FILEUPLOAD_ERROR . $uploader->getErrors());
                }
            }
        } else {
            $categoryObj->setVar('image', Request::getString('image', '', 'POST'));
        }
        $categoryObj->setVar('parentid', Request::getInt('parentid', 0, 'POST'));

        $applyall = Request::getInt('applyall', 0, 'POST');
        $categoryObj->setVar('weight', Request::getInt('weight', 1, 'POST'));

        // Groups and permissions
        $grpread       = Request::getArray('groupsRead', [], 'POST');
        $grpsubmit     = Request::getArray('groupsSubmit', [], 'POST');
        $grpmoderation = Request::getArray('groupsModeration', [], 'POST');

        $categoryObj->setVar('name', Request::getString('name', '', 'POST'));

        //Added by skalpa: custom template support
        $categoryObj->setVar('template', Request::getString('template', '', 'POST'));
        $categoryObj->setVar('template_item', Request::getString('template_item', '', 'POST'));
        $categoryObj->setVar('meta_description', Request::getString('meta_description', '', 'POST'));
        $categoryObj->setVar('meta_keywords', Request::getString('meta_keywords', '', 'POST'));
        $categoryObj->setVar('short_url', Request::getString('short_url', '', 'POST'));
        $categoryObj->setVar('moderator', Request::getInt('moderator', 0, 'POST'));
        $categoryObj->setVar('description', Request::getString('description', '', 'POST'));
        $categoryObj->setVar('header', Request::getText('header', '', 'POST'));

        if ($categoryObj->isNew()) {
            $redirectMsg = _AM_PUBLISHER_CATCREATED;
            $redirectTo  = 'category.php?op=mod';
        } else {
            $redirectMsg = _AM_PUBLISHER_COLMODIFIED;
            $redirectTo  = 'category.php';
        }

        if (!$categoryObj->store()) {
            redirect_header('<script>javascript:history.go(-1)</script>', 3, _AM_PUBLISHER_CATEGORY_SAVE_ERROR . Utility::formatErrors($categoryObj->getErrors()));
        }
        // TODO : put this function in the category class
        Utility::saveCategoryPermissions($grpread, $categoryObj->categoryid(), 'category_read');
        Utility::saveCategoryPermissions($grpsubmit, $categoryObj->categoryid(), 'item_submit');
        Utility::saveCategoryPermissions($grpmoderation, $categoryObj->categoryid(), 'category_moderation');

        //Added by fx2024
        $parentCat = $categoryObj->categoryid();
        $sizeof    = count(Request::getArray('scname', [], 'POST'));
        for ($i = 0; $i < $sizeof; ++$i) {
            $temp = Request::getArray('scname', [], 'POST');
            if ('' != $temp[$i]) {
                $categoryObj = $helper->getHandler('Category')
                                      ->create();
                $temp2       = Request::getArray('scname', [], 'POST');
                $categoryObj->setVar('name', $temp2[$i]);
                $categoryObj->setVar('parentid', $parentCat);

                if (!$categoryObj->store()) {
                    redirect_header('<script>javascript:history.go(-1)</script>', 3, _AM_PUBLISHER_SUBCATEGORY_SAVE_ERROR . Utility::formatErrors($categoryObj->getErrors()));
                }
                // TODO : put this function in the category class
                Utility::saveCategoryPermissions($grpread, $categoryObj->categoryid(), 'category_read');
                Utility::saveCategoryPermissions($grpsubmit, $categoryObj->categoryid(), 'item_submit');
                Utility::saveCategoryPermissions($grpmoderation, $categoryObj->categoryid(), 'category_moderation');
            }
        }
        //end of fx2024 code
        redirect_header($redirectTo, 2, $redirectMsg);
        break;
    //Added by fx2024

    case 'addsubcats':
        $categoryid    = 0;
        $numberSubcats = Request::getInt('nb_subcats', 0, 'POST') + Request::getInt('nb_sub_yet', 0, 'POST');

        $categoryObj = $helper->getHandler('Category')
                              ->create();
        $categoryObj->setVar('name', Request::getString('name', '', 'POST'));
        $categoryObj->setVar('description', Request::getString('description', '', 'POST'));
        $categoryObj->setVar('weight', Request::getInt('weight', 0, 'POST'));
        if (isset($parentCat)) {
            $categoryObj->setVar('parentid', $parentCat);
        }

        Utility::cpHeader();
        Utility::editCategory(true, $categoryid, $numberSubcats, $categoryObj);
        exit();
    //end of fx2024 code

    case 'cancel':
        redirect_header('category.php', 1, sprintf(_AM_PUBLISHER_BACK2IDX, ''));
        break;
    case 'default':
    default:
        Utility::cpHeader();
        //publisher_adminMenu(1, _AM_PUBLISHER_CATEGORIES);

        echo "<br>\n";
        echo '<form><div style="margin-bottom: 12px;">';
        echo "<input type='button' name='button' onclick=\"location='category.php?op=mod'\" value='" . _AM_PUBLISHER_CATEGORY_CREATE . "'>&nbsp;&nbsp;";
        //echo "<input type='button' name='button' onclick=\"location='item.php?op=mod'\" value='" . _AM_PUBLISHER_CREATEITEM . "'>&nbsp;&nbsp;";
        echo '</div></form>';

        // Creating the objects for top categories
        $categoriesObj = $helper->getHandler('Category')
                                ->getCategories($helper->getConfig('idxcat_perpage'), $startcategory, 0);

        Utility::openCollapsableBar('createdcategories', 'createdcategoriesicon', _AM_PUBLISHER_CATEGORIES_TITLE, _AM_PUBLISHER_CATEGORIES_DSC);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo '<tr>';
        echo "<th width='20' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEMCATEGORY_ID . '</strong></td>';
        echo "<th class='bg3' align='left'><strong>" . _AM_PUBLISHER_ITEMCATEGORYNAME . '</strong></td>';
        echo "<th width='60' class='bg3' width='65' align='center'><strong>" . _CO_PUBLISHER_WEIGHT . '</strong></td>';
        echo "<th width='60' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ACTION . '</strong></td>';
        echo '</tr>';
        $totalCategories = $helper->getHandler('Category')
                                  ->getCategoriesCount(0);
        if (count($categoriesObj) > 0) {
            foreach ($categoriesObj as $key => $thiscat) {
                Utility::displayCategory($thiscat);
            }
            unset($key);
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOCAT . '</td>';
            echo '</tr>';
            $categoryid = '0';
        }
        echo "</table>\n";
        require_once $GLOBALS['xoops']->path('class/pagenav.php');
        $pagenav = new \XoopsPageNav($totalCategories, $helper->getConfig('idxcat_perpage'), $startcategory, 'startcategory');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo '<br>';
        Utility::closeCollapsableBar('createdcategories', 'createdcategoriesicon');
        echo '<br>';
        //editcat(false);
        break;
}

require_once __DIR__ . '/admin_footer.php';
