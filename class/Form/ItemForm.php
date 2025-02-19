<?php declare(strict_types=1);

namespace XoopsModules\Publisher\Form;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *  Publisher form class
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         https://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

use Xmf\Request;
use XoopsModules\Publisher\Common\Configurator;
use XoopsModules\Publisher\Constants;
use XoopsModules\Publisher\FormDateTime;
use XoopsModules\Publisher\Helper;
use XoopsModules\Publisher\Item;
use XoopsModules\Publisher\Utility;

require_once \dirname(__DIR__, 2) . '/include/common.php';

\xoops_load('XoopsFormLoader');
\xoops_load('XoopsLists');
require_once $GLOBALS['xoops']->path('class/tree.php');
//require_once PUBLISHER_ROOT_PATH . '/class/formdatetime.php';
//require_once PUBLISHER_ROOT_PATH . '/class/themetabform.php';

/**
 * Class ItemForm
 */
class ItemForm extends ThemeTabForm
{
    public $checkperm = true;
    public $tabs      = [
        \_CO_PUBLISHER_TAB_MAIN   => 'mainTab',
        \_CO_PUBLISHER_TAB_IMAGES => 'imagesTab',
        \_CO_PUBLISHER_TAB_FILES  => 'filesTab',
        \_CO_PUBLISHER_TAB_OTHERS => 'othersTab',
    ];
    public $mainTab   = [
        Constants::PUBLISHER_SUBTITLE,
        Constants::PUBLISHER_ITEM_SHORT_URL,
        Constants::PUBLISHER_ITEM_TAG,
        Constants::PUBLISHER_SUMMARY,
        Constants::PUBLISHER_DOHTML,
        Constants::PUBLISHER_DOSMILEY,
        Constants::PUBLISHER_DOXCODE,
        Constants::PUBLISHER_DOIMAGE,
        Constants::PUBLISHER_DOLINEBREAK,
        Constants::PUBLISHER_DATESUB,
        Constants::PUBLISHER_STATUS,
        Constants::PUBLISHER_AUTHOR_ALIAS,
        Constants::PUBLISHER_NOTIFY,
        Constants::PUBLISHER_AVAILABLE_PAGE_WRAP,
        Constants::PUBLISHER_UID,
        Constants::PUBLISHER_VOTETYPE,
    ];
    public $imagesTab = [
        Constants::PUBLISHER_IMAGE_ITEM,
    ];
    public $filesTab  = [
        Constants::PUBLISHER_ITEM_UPLOAD_FILE,
    ];
    public $othersTab = [
        Constants::PUBLISHER_ITEM_META_KEYWORDS,
        Constants::PUBLISHER_ITEM_META_DESCRIPTION,
        Constants::PUBLISHER_WEIGHT,
        Constants::PUBLISHER_ALLOWCOMMENTS,
    ];

    /**
     * @param $checkperm
     */
    public function setCheckPermissions($checkperm): void
    {
        $this->checkperm = (bool)$checkperm;
    }

    /**
     * @param $item
     *
     * @return bool
     */
    public function isGranted($item)
    {
        $helper = Helper::getInstance();
        $ret    = false;
        if (!$this->checkperm
            || $helper->getHandler('Permission')
                      ->isGranted('form_view', $item)) {
            $ret = true;
        }

        return $ret;
    }

    /**
     * @param $tab
     *
     * @return bool
     */
    public function hasTab($tab)
    {
        if (!isset($tab, $this->tabs[$tab])) {
            return false;
        }

        $tabRef = $this->tabs[$tab];
        $items  = $this->$tabRef;
        foreach ($items as $item) {
            if ($this->isGranted($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Item $obj
     *
     * @return $this
     */
    public function createElements($obj)
    {
        $helper       = Helper::getInstance();
        $timeoffset   = null;
        $configurator = new Configurator();
        $icons        = $configurator->icons;

        $allowedEditors = Utility::getEditors(
            $helper->getHandler('Permission')
                   ->getGrantedItems('editors')
        );

        if (\is_object($GLOBALS['xoopsUser'])) {
            $group      = $GLOBALS['xoopsUser']->getGroups();
            $currentUid = $GLOBALS['xoopsUser']->uid();
            $timeoffset = $GLOBALS['xoopsUser']->getVar('timezone_offset');
        } else {
            $group      = [XOOPS_GROUP_ANONYMOUS];
            $currentUid = 0;
        }

        $this->setExtra('enctype="multipart/form-data"');

        $this->startTab(\_CO_PUBLISHER_TAB_MAIN);

        // Category
        $categoryFormSelect = new \XoopsFormSelect(\_CO_PUBLISHER_CATEGORY, 'categoryid', $obj->getVar('categoryid', 'e'));
        $categoryFormSelect->setDescription(\_CO_PUBLISHER_CATEGORY_DSC);
        $categoryFormSelect->addOptionArray(
            $helper->getHandler('Category')
                   ->getCategoriesForSubmit()
        );
        $this->addElement($categoryFormSelect);

        // ITEM TITLE
        $this->addElement(new \XoopsFormText(\_CO_PUBLISHER_TITLE, 'title', 50, 255, $obj->getVar('title', 'e')), true);

        // SUBTITLE
        if ($this->isGranted(Constants::PUBLISHER_SUBTITLE)) {
            $this->addElement(new \XoopsFormText(\_CO_PUBLISHER_SUBTITLE, 'subtitle', 50, 255, $obj->getVar('subtitle', 'e')));
        }

        // SHORT URL
        if ($this->isGranted(Constants::PUBLISHER_ITEM_SHORT_URL)) {
            $textShortUrl = new \XoopsFormText(\_CO_PUBLISHER_ITEM_SHORT_URL, 'item_short_url', 50, 255, $obj->short_url('e'));
            $textShortUrl->setDescription(\_CO_PUBLISHER_ITEM_SHORT_URL_DSC);
            $this->addElement($textShortUrl);
        }

        // TAGS
        if (\xoops_isActiveModule('tag') && \class_exists(\XoopsModules\Tag\FormTag::class) && $this->isGranted(Constants::PUBLISHER_ITEM_TAG)) {
            $textTags = new \XoopsModules\Tag\FormTag('item_tag', 60, 255, $obj->getVar('item_tag', 'e'), 0);
            $textTags->setClass('form-control');
            $this->addElement($textTags);
        }

        // SELECT EDITOR
        $nohtml = !$obj->dohtml();
        if (1 === \count($allowedEditors)) {
            $editor = $allowedEditors[0];
        } elseif (\count($allowedEditors) > 0) {
            $editor = Request::getString('editor', '', 'POST');
            if (!empty($editor)) {
                Utility::setCookieVar('publisher_editor', $editor);
            } else {
                $editor = Utility::getCookieVar('publisher_editor');
                if (empty($editor) && \is_object($GLOBALS['xoopsUser'])) {
                    //                    $editor = @ $GLOBALS['xoopsUser']->getVar('publisher_editor'); // Need set through user profile
                    $editor = $GLOBALS['xoopsUser']->getVar('publisher_editor') ?? ''; // Need set through user profile
                }
            }
            $editor = (empty($editor) || !\in_array($editor, $allowedEditors, true)) ? $helper->getConfig('submit_editor') : $editor;

            $formEditor = new \XoopsFormSelectEditor($this, 'editor', $editor, $nohtml, $allowedEditors);
            $this->addElement($formEditor);
        } else {
            $editor = $helper->getConfig('submit_editor');
        }

        $editorConfigs           = [];
        $editorConfigs['rows']   = !$helper->getConfig('submit_editor_rows') ? 35 : $helper->getConfig('submit_editor_rows');
        $editorConfigs['cols']   = !$helper->getConfig('submit_editor_cols') ? 60 : $helper->getConfig('submit_editor_cols');
        $editorConfigs['width']  = !$helper->getConfig('submit_editor_width') ? '100%' : $helper->getConfig('submit_editor_width');
        $editorConfigs['height'] = !$helper->getConfig('submit_editor_height') ? '400px' : $helper->getConfig('submit_editor_height');

        // SUMMARY
        if ($this->isGranted(Constants::PUBLISHER_SUMMARY)) {
            // Description
            //$summaryText = new \XoopsFormTextArea(_CO_PUBLISHER_SUMMARY, 'summary', $obj->getVar('summary', 'e'), 7, 60);
            $editorConfigs['name']  = 'summary';
            $editorConfigs['value'] = $obj->getVar('summary', 'e');
            $summaryText            = new \XoopsFormEditor(\_CO_PUBLISHER_SUMMARY, $editor, $editorConfigs, $nohtml, $onfailure = null);
            $summaryText->setDescription(\_CO_PUBLISHER_SUMMARY_DSC);
            $this->addElement($summaryText);
        }

        // BODY
        $editorConfigs['name']  = 'body';
        $editorConfigs['value'] = $obj->getVar('body', 'e');
        $bodyText               = new \XoopsFormEditor(\_CO_PUBLISHER_BODY, $editor, $editorConfigs, $nohtml, $onfailure = null);
        $bodyText->setDescription(\_CO_PUBLISHER_BODY_DSC);
        $this->addElement($bodyText);

        // VARIOUS OPTIONS
        if ($this->isGranted(Constants::PUBLISHER_DOHTML)
            || $this->isGranted(Constants::PUBLISHER_DOSMILEY)
            || $this->isGranted(Constants::PUBLISHER_DOXCODE)
            || $this->isGranted(Constants::PUBLISHER_DOIMAGE)
            || $this->isGranted(Constants::PUBLISHER_DOLINEBREAK)) {
            if ($this->isGranted(Constants::PUBLISHER_DOHTML)) {
                $htmlRadio = new \XoopsFormRadioYN(\_CO_PUBLISHER_DOHTML, 'dohtml', $obj->dohtml(), \_YES, \_NO);
                $this->addElement($htmlRadio);
            }
            if ($this->isGranted(Constants::PUBLISHER_DOSMILEY)) {
                $smiley_radio = new \XoopsFormRadioYN(\_CO_PUBLISHER_DOSMILEY, 'dosmiley', $obj->dosmiley(), \_YES, \_NO);
                $this->addElement($smiley_radio);
            }
            if ($this->isGranted(Constants::PUBLISHER_DOXCODE)) {
                $xcode_radio = new \XoopsFormRadioYN(\_CO_PUBLISHER_DOXCODE, 'doxcode', $obj->doxcode(), \_YES, \_NO);
                $this->addElement($xcode_radio);
            }
            if ($this->isGranted(Constants::PUBLISHER_DOIMAGE)) {
                $image_radio = new \XoopsFormRadioYN(\_CO_PUBLISHER_DOIMAGE, 'doimage', $obj->doimage(), \_YES, \_NO);
                $this->addElement($image_radio);
            }
            if ($this->isGranted(Constants::PUBLISHER_DOLINEBREAK)) {
                $linebreak_radio = new \XoopsFormRadioYN(\_CO_PUBLISHER_DOLINEBREAK, 'dolinebreak', $obj->dobr(), \_YES, \_NO);
                $this->addElement($linebreak_radio);
            }
        }

        // Available pages to wrap
        if ($this->isGranted(Constants::PUBLISHER_AVAILABLE_PAGE_WRAP)) {
            $wrapPages              = \XoopsLists::getHtmlListAsArray(Utility::getUploadDir(true, 'content'));
            $availableWrapPagesText = [];
            foreach ($wrapPages as $page) {
                $availableWrapPagesText[] = "<span onclick='publisherPageWrap(\"body\", \"[pagewrap=$page] \");' onmouseover='style.cursor=\"pointer\"'>$page</span>";
            }
            $availableWrapPages = new \XoopsFormLabel(\_CO_PUBLISHER_AVAILABLE_PAGE_WRAP, \implode(', ', $availableWrapPagesText));
            $availableWrapPages->setDescription(\_CO_PUBLISHER_AVAILABLE_PAGE_WRAP_DSC);
            $this->addElement($availableWrapPages);
        }

        //VOTING TYPE =====================================
        //        if ($this->isGranted(Constants::PUBLISHER_VOTETYPE)) {
        $groups = $GLOBALS['xoopsUser'] ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = $helper->getHandler('GroupPerm');
        $moduleId         = $helper->getModule()
                                   ->getVar('mid');
        if ($helper->getConfig('perm_rating') && $grouppermHandler->checkRight('global', \_PUBLISHER_RATE, $groups, $moduleId)) {
            $options = [
                Constants::RATING_NONE     => \_MI_PUBLISHER_RATING_NONE,
                Constants::RATING_5STARS   => \_MI_PUBLISHER_RATING_5STARS,
                Constants::RATING_10STARS  => \_MI_PUBLISHER_RATING_10STARS,
                Constants::RATING_LIKES    => \_MI_PUBLISHER_RATING_LIKES,
                Constants::RATING_10NUM    => \_MI_PUBLISHER_RATING_10NUM,
                Constants::RATING_REACTION => \_MI_PUBLISHER_RATING_REACTION,
            ];

            $votetypeSelect = new \XoopsFormSelect(\_MI_PUBLISHER_RATINGBARS, 'votetype', $obj->getVar('votetype'));
            $votetypeSelect->addOptionArray($options);
            //                $votetypeSelect->setDescription(\_MI_PUBLISHER_RATINGBARS_DESC);
            $this->addElement($votetypeSelect);
            unset($votetypeSelect);
        }
        //        }
        //VOTING TYPE END =====================================

        $userUid = $obj->getVar('itemid') > 0 ? $obj->uid() : $currentUid;
        if ($this->isGranted(Constants::PUBLISHER_UID)) {
            $this->addElement(new \XoopsFormSelectUser(\_CO_PUBLISHER_UID, 'uid', false, $userUid, 1, false), false);
        }

        // Uid
        /*  We need to retreive the users manually because for some reason, on the frxoops.org server,
         the method users::getobjects encounters a memory error
         */ // Trabis : well, maybe is because you are getting 6000 objects into memory , no??? LOL
        /*
        if ($this->isGranted(Constants::PUBLISHER_UID)) {
            $uidSelect = new \XoopsFormSelect(_CO_PUBLISHER_UID, 'uid', $obj->uid(), 1, false);
            $uidSelect->setDescription(_CO_PUBLISHER_UID_DSC);
            $sql           = 'SELECT uid, uname FROM ' . $obj->db->prefix('users') . ' ORDER BY uname ASC';
            $result        = $obj->db->query($sql);
            $usersArray     = [];
            $usersArray[0] = $GLOBALS['xoopsConfig']['anonymous'];
            while (($myrow = $obj->db->fetchArray($result)) !== false) {
                $usersArray[$myrow['uid']] = $myrow['uname'];
            }
            $uidSelect->addOptionArray($usersArray);
            $this->addElement($uidSelect);
        }
        */

        /* else {
        $hidden = new \XoopsFormHidden('uid', $obj->uid());
        $this->addElement($hidden);
        unset($hidden);
        }*/

        // Author ALias
        if ($this->isGranted(Constants::PUBLISHER_AUTHOR_ALIAS)) {
            $element = new \XoopsFormText(\_CO_PUBLISHER_AUTHOR_ALIAS, 'author_alias', 50, 255, $obj->getVar('author_alias', 'e'));
            $element->setDescription(\_CO_PUBLISHER_AUTHOR_ALIAS_DSC);
            $this->addElement($element);
            unset($element);
        }

        // STATUS
        if ($this->isGranted(Constants::PUBLISHER_STATUS)) {
            $options      = [
                Constants::PUBLISHER_STATUS_SUBMITTED => \_CO_PUBLISHER_SUBMITTED,
                Constants::PUBLISHER_STATUS_PUBLISHED => \_CO_PUBLISHER_PUBLISHED,
                Constants::PUBLISHER_STATUS_OFFLINE   => \_CO_PUBLISHER_OFFLINE,
                Constants::PUBLISHER_STATUS_REJECTED  => \_CO_PUBLISHER_REJECTED,
            ];
            $statusSelect = new \XoopsFormSelect(\_CO_PUBLISHER_STATUS, 'status', $obj->getVar('status'));
            $statusSelect->addOptionArray($options);
            $statusSelect->setDescription(\_CO_PUBLISHER_STATUS_DSC);
            $this->addElement($statusSelect);
            unset($statusSelect);
        }

        // Datesub
        if ($this->isGranted(Constants::PUBLISHER_DATESUB)) {
            if ($obj->isNew()) {
                $datesub = \time();
            } else {
                $datesub = (0 == $obj->getVar('datesub')) ? \time() : $obj->getVar('datesub');
            }
            $datesub_datetime = new FormDateTime(\_CO_PUBLISHER_DATESUB, 'datesub', $size = 15, $datesub, true, true);
            // $datesub_datetime = new \XoopsFormDateTime(_CO_PUBLISHER_DATESUB, 'datesub', $size = 15, $datesub, true, true);

            $datesub_datetime->setDescription(\_CO_PUBLISHER_DATESUB_DSC);
            $this->addElement($datesub_datetime);
        }

        // Date expire
        if ($this->isGranted(Constants::PUBLISHER_DATEEXPIRE)) {
            if ($obj->isNew()) {
                $dateexpire     = \time();
                $dateexpire_opt = 0;
            } elseif (0 == $obj->getVar('dateexpire')) {
                $dateexpire_opt = 0;
                $dateexpire     = \time();
            } else {
                $dateexpire_opt = 1;
                $dateexpire     = $obj->getVar('dateexpire');
            }

            $dateExpireYesNo     = new \XoopsFormRadioYN('', 'use_expire_yn', $dateexpire_opt);
            $dateexpire          = (int)\formatTimestamp($dateexpire, 'U', $timeoffset); //set to user timezone
            $dateexpire_datetime = new \XoopsFormDateTime('', 'dateexpire', $size = 15, $dateexpire, true);
            if (0 == $dateexpire_opt) {
                $dateexpire_datetime->setExtra('disabled="disabled"');
            }

            $dateExpireTray = new \XoopsFormElementTray(\_CO_PUBLISHER_DATEEXPIRE, '');
            $dateExpireTray->setDescription(\_CO_PUBLISHER_DATEEXPIRE_DSC);
            $dateExpireTray->addElement($dateExpireYesNo);
            $dateExpireTray->addElement($dateexpire_datetime);
            $this->addElement($dateExpireTray);
        }

        // NOTIFY ON PUBLISH
        if ($this->isGranted(Constants::PUBLISHER_NOTIFY)) {
            $notify_radio = new \XoopsFormRadioYN(\_CO_PUBLISHER_NOTIFY, 'notify', $obj->notifypub(), \_YES, \_NO);
            $this->addElement($notify_radio);
        }

        if ($this->hasTab(\_CO_PUBLISHER_TAB_IMAGES)) {
            $this->startTab(\_CO_PUBLISHER_TAB_IMAGES);
        }

        // IMAGE ---------------------------------------
        if ($this->isGranted(Constants::PUBLISHER_IMAGE_ITEM)) {
            $objimages      = $obj->getImages();
            $mainarray      = \is_object($objimages['main']) ? [$objimages['main']] : [];
            $mergedimages   = \array_merge($mainarray, $objimages['others']);
            $objimage_array = [];
            foreach ($mergedimages as $imageObj) {
                $objimage_array[$imageObj->getVar('image_name')] = $imageObj->getVar('image_nicename');
            }

            /** @var \XoopsImagecategoryHandler $imgcatHandler */
            $imgcatHandler = \xoops_getHandler('imagecategory');
            if (\method_exists($imgcatHandler, 'getListByPermission')) {
                $catlist = $imgcatHandler->getListByPermission($group, 'imgcat_read', 1);
            } else {
                $catlist = $imgcatHandler->getList($group, 'imgcat_read', 1);
            }
            $imgcatConfig = $helper->getConfig('submit_imgcat');
            if (\in_array(Constants::PUBLISHER_IMGCAT_ALL, $imgcatConfig, true)) {
                $catids = \array_keys($catlist);
            } else {
                // compare selected in options with readable of user
                $catlist = \array_intersect($catlist, $imgcatConfig);
                $catids  = \array_keys($catlist);
            }

            $imageObjs = [];
            if (!empty($catids)) {
                /** @var \XoopsImageHandler $imageHandler */
                $imageHandler = \xoops_getHandler('image');
                $criteria     = new \CriteriaCompo(new \Criteria('imgcat_id', '(' . \implode(',', $catids) . ')', 'IN'));
                $criteria->add(new \Criteria('image_display', 1));
                $criteria->setSort('image_nicename');
                $criteria->order = 'ASC'; // patch for XOOPS <= 2.5.10, does not set order correctly using setOrder() method
                $imageObjs       = $imageHandler->getObjects($criteria, true);
                unset($criteria);
            }
            $image_array = [];
            foreach ($imageObjs as $imageObj) {
                $image_array[$imageObj->getVar('image_name')] = $imageObj->getVar('image_nicename');
            }

            $image_array = \array_diff($image_array, $objimage_array);

            $imageSelect = new \XoopsFormSelect('', 'image_notused', '', 5);
            $imageSelect->addOptionArray($image_array);
            $imageSelect->setExtra("onchange='showImgSelected(\"image_display\", \"image_notused\", \"uploads/\", \"\", \"" . XOOPS_URL . "\")'");
            //$imageSelect->setExtra( "onchange='appendMySelectOption(\"image_notused\", \"image_item\")'");
            unset($image_array);

            $imageSelect2 = new \XoopsFormSelect('', 'image_item', '', 5, true);
            $imageSelect2->addOptionArray($objimage_array);
            $imageSelect2->setExtra("onchange='publisher_updateSelectOption(\"image_item\", \"image_featured\"), showImgSelected(\"image_display\", \"image_item\", \"uploads/\", \"\", \"" . XOOPS_URL . "\")'");

            $buttonadd = new \XoopsFormButton('', 'buttonadd', \_CO_PUBLISHER_ADD);
            $buttonadd->setExtra("onclick='publisher_appendSelectOption(\"image_notused\", \"image_item\"), publisher_updateSelectOption(\"image_item\", \"image_featured\")'");

            $buttonremove = new \XoopsFormButton('', 'buttonremove', \_CO_PUBLISHER_REMOVE);
            $buttonremove->setExtra("onclick='publisher_appendSelectOption(\"image_item\", \"image_notused\"), publisher_updateSelectOption(\"image_item\", \"image_featured\")'");

            $opentable  = new \XoopsFormLabel('', '<table><tr><td>');
            $addcol     = new \XoopsFormLabel('', '</td><td>');
            $addbreak   = new \XoopsFormLabel('', '<br>');
            $closetable = new \XoopsFormLabel('', '</td></tr></table>');

            $GLOBALS['xoTheme']->addScript(PUBLISHER_URL . '/assets/js/ajaxupload.3.9.js');
            $js_data  = new \XoopsFormLabel(
                '', '

<script type= "text/javascript">
$publisher(document).ready(function () {
    var button = $publisher("#publisher_upload_button"), interval;
    new AjaxUpload(button,{
        action: "' . PUBLISHER_URL . '/include/ajax_upload.php", // I disabled uploads in this example for security reasons
        responseType: "text/html",
        name: "publisher_upload_file",
        onSubmit : function (file, ext) {
            // change button text, when user selects file
            $publisher("#publisher_upload_message").html(" ");
            button.html("<img src=\'' . PUBLISHER_URL . '/assets/images/loadingbar.gif\'>"); this.setData({
                "image_nicename": $publisher("#image_nicename").val(),
                "imgcat_id" : $publisher("#imgcat_id").val()
            });
            // If you want to allow uploading only 1 file at time,
            // you can disable upload button
            //this.disable();
            interval = window.setInterval(function () {
            }, 200);
        },
        onComplete: function (file, response) {
            button.text("' . \_CO_PUBLISHER_IMAGE_UPLOAD_NEW . '");
            window.clearInterval(interval);
            // enable upload button
            this.enable();
            // add file to the list
            var result = eval(response);
            if ("success" == result[0]) {
                 $publisher("#image_item").append("<option value=\'" + result[1] + "\' selected=\'selected\'>" + result[2] + "</option>");
                 publisher_updateSelectOption(\'image_item\', \'image_featured\');
                 showImgSelected(\'image_display\', \'image_item\', \'uploads/\', \'\', \'' . XOOPS_URL . '\')
            } else {
                 $publisher("#publisher_upload_message").html("<div class=\'errorMsg\'>" + result[1] + "</div>");
            }
        }
    });
});
</script>

'
            );
            $messages = new \XoopsFormLabel('', "<div id='publisher_upload_message'></div>");
            $button   = new \XoopsFormLabel('', "<div id='publisher_upload_button'>" . \_CO_PUBLISHER_IMAGE_UPLOAD_NEW . '</div>');
            $nicename = new \XoopsFormText('', 'image_nicename', 30, 30, \_CO_PUBLISHER_IMAGE_NICENAME);

            // $imgcatHandler = xoops_getHandler('imagecategory');
            // if (method_exists($imgcatHandler, 'getListByPermission')) {
            // $catlist = $imgcatHandler->getListByPermission($group, 'imgcat_read', 1);
            // } else {
            // $catlist = $imgcatHandler->getList($group, 'imgcat_read', 1);
            // }
            $imagecat = new \XoopsFormSelect('', 'imgcat_id', '', 1);
            $imagecat->addOptionArray($catlist);

            $imageUploadTray = new \XoopsFormElementTray(\_CO_PUBLISHER_IMAGE_UPLOAD, '');
            $imageUploadTray->addElement($js_data);
            $imageUploadTray->addElement($messages);
            $imageUploadTray->addElement($opentable);
            $imageUploadTray->addElement($imagecat);
            $imageUploadTray->addElement($addbreak);
            $imageUploadTray->addElement($nicename);
            $imageUploadTray->addElement($addbreak);
            $imageUploadTray->addElement($button);
            $imageUploadTray->addElement($closetable);
            $this->addElement($imageUploadTray);

            $imageTray = new \XoopsFormElementTray(\_CO_PUBLISHER_IMAGE_ITEMS, '');
            $imageTray->addElement($opentable);

            $imageTray->addElement($imageSelect);
            $imageTray->addElement($addbreak);
            $imageTray->addElement($buttonadd);

            $imageTray->addElement($addcol);

            $imageTray->addElement($imageSelect2);
            $imageTray->addElement($addbreak);
            $imageTray->addElement($buttonremove);

            $imageTray->addElement($closetable);
            $imageTray->setDescription(\_CO_PUBLISHER_IMAGE_ITEMS_DSC);
            $this->addElement($imageTray);

            $imagename    = \is_object($objimages['main']) ? $objimages['main']->getVar('image_name') : '';
            $imageforpath = ('' != $imagename) ? $imagename : 'blank.gif';

            $imageSelect3 = new \XoopsFormSelect(\_CO_PUBLISHER_IMAGE_ITEM, 'image_featured', $imagename, 1);
            $imageSelect3->addOptionArray($objimage_array);
            $imageSelect3->setExtra("onchange='showImgSelected(\"image_display\", \"image_featured\", \"uploads/\", \"\", \"" . XOOPS_URL . "\")'");
            $imageSelect3->setDescription(\_CO_PUBLISHER_IMAGE_ITEM_DSC);
            $this->addElement($imageSelect3);

            $image_preview = new \XoopsFormLabel(\_CO_PUBLISHER_IMAGE_PREVIEW, "<img src='" . XOOPS_URL . '/uploads/' . $imageforpath . "' name='image_display' id='image_display' alt=''>");
            $this->addElement($image_preview);
        }

        // FILES -----------------------------------
        if ($this->hasTab(\_CO_PUBLISHER_TAB_FILES)) {
            $this->startTab(\_CO_PUBLISHER_TAB_FILES);
        }
        // File upload UPLOAD
        if ($this->isGranted(Constants::PUBLISHER_ITEM_UPLOAD_FILE)) {
            // NAME
            $nameText = new \XoopsFormText(\_CO_PUBLISHER_FILENAME, 'item_file_name', 50, 255, '');
            $nameText->setDescription(\_CO_PUBLISHER_FILE_NAME_DSC);
            $this->addElement($nameText);
            unset($nameText);

            // DESCRIPTION
            $descriptionText = new \XoopsFormTextArea(\_CO_PUBLISHER_FILE_DESCRIPTION, 'item_file_description', '');
            $descriptionText->setDescription(\_CO_PUBLISHER_FILE_DESCRIPTION_DSC);
            $this->addElement($descriptionText);
            unset($descriptionText);

            $statusSelect = new \XoopsFormRadioYN(\_CO_PUBLISHER_FILE_STATUS, 'item_file_status', 1); //1 - active
            $statusSelect->setDescription(\_CO_PUBLISHER_FILE_STATUS_DSC);
            $this->addElement($statusSelect);
            unset($statusSelect);

            $fileBox = new \XoopsFormFile(\_CO_PUBLISHER_ITEM_UPLOAD_FILE, 'item_upload_file', 0);
            $fileBox->setDescription(\_CO_PUBLISHER_ITEM_UPLOAD_FILE_DSC);
            $fileBox->setExtra("size ='50'");
            $this->addElement($fileBox);
            unset($fileBox);

            if (!$obj->isNew()) {
                $filesObj = $helper->getHandler('File')
                                   ->getAllFiles($obj->itemid());
                if (\count($filesObj) > 0) {
                    $table = "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
                    $table .= '<tr>';
                    $table .= "<td width='50' class='bg3' align='center'><strong>ID</strong></td>";
                    $table .= "<td width='150' class='bg3' align='left'><strong>" . \_AM_PUBLISHER_FILENAME . '</strong></td>';
                    $table .= "<td class='bg3' align='left'><strong>" . \_AM_PUBLISHER_DESCRIPTION . '</strong></td>';
                    $table .= "<td width='60' class='bg3' align='center'><strong>" . \_AM_PUBLISHER_HITS . '</strong></td>';
                    $table .= "<td width='100' class='bg3' align='center'><strong>" . \_AM_PUBLISHER_UPLOADED_DATE . '</strong></td>';
                    $table .= "<td width='60' class='bg3' align='center'><strong>" . \_AM_PUBLISHER_ACTION . '</strong></td>';
                    $table .= '</tr>';

                    foreach ($filesObj as $fileObj) {
                        $modify      = "<a href='file.php?op=mod&fileid=" . $fileObj->fileid() . "'>" . $icons['edit'] . '</a>';
                        $delete      = "<a href='file.php?op=del&fileid=" . $fileObj->fileid() . "'>" . $icons['delete'] . '</a>';
                        $not_visible = '';
                        if (0 == $fileObj->status()) {
                            $not_visible = "<img src='" . PUBLISHER_URL . "/assets/images/no.gif'>";
                        }
                        $table .= '<tr>';
                        $table .= "<td class='head' align='center'>" . $fileObj->getVar('fileid') . '</td>';
                        $table .= "<td class='odd' align='left'>" . $not_visible . $fileObj->getFileLink() . '</td>';
                        $table .= "<td class='even' align='left'>" . $fileObj->description() . '</td>';
                        $table .= "<td class='even' align='center'>" . $fileObj->counter();
                        $table .= "<td class='even' align='center'>" . $fileObj->getDatesub() . '</td>';
                        $table .= "<td class='even' align='center'> $modify $delete </td>";
                        $table .= '</tr>';
                    }
                    $table .= '</table>';

                    $files_box = new \XoopsFormLabel(\_CO_PUBLISHER_FILES_LINKED, $table);
                    $this->addElement($files_box);
                    unset($files_box, $filesObj, $fileObj);
                }
            }
        }

        // OTHERS -----------------------------------
        if ($this->hasTab(\_CO_PUBLISHER_TAB_OTHERS)) {
            $this->startTab(\_CO_PUBLISHER_TAB_OTHERS);
        }
        //$this->startTab(_CO_PUBLISHER_TAB_META);
        // Meta Keywords
        if ($this->isGranted(Constants::PUBLISHER_ITEM_META_KEYWORDS)) {
            $text_meta_keywords = new \XoopsFormTextArea(\_CO_PUBLISHER_ITEM_META_KEYWORDS, 'item_meta_keywords', $obj->meta_keywords('e'), 7, 60);
            $text_meta_keywords->setDescription(\_CO_PUBLISHER_ITEM_META_KEYWORDS_DSC);
            $this->addElement($text_meta_keywords);
        }

        // Meta Description
        if ($this->isGranted(Constants::PUBLISHER_ITEM_META_DESCRIPTION)) {
            $text_meta_description = new \XoopsFormTextArea(\_CO_PUBLISHER_ITEM_META_DESCRIPTION, 'item_meta_description', $obj->meta_description('e'), 7, 60);
            $text_meta_description->setDescription(\_CO_PUBLISHER_ITEM_META_DESCRIPTION_DSC);
            $this->addElement($text_meta_description);
        }

        //$this->startTab(_CO_PUBLISHER_TAB_PERMISSIONS);

        // COMMENTS
        if ($this->isGranted(Constants::PUBLISHER_ALLOWCOMMENTS)) {
            $addcomments_radio = new \XoopsFormRadioYN(\_CO_PUBLISHER_ALLOWCOMMENTS, 'allowcomments', $obj->cancomment(), \_YES, \_NO);
            $this->addElement($addcomments_radio);
        }

        // WEIGHT
        if ($this->isGranted(Constants::PUBLISHER_WEIGHT)) {
            $this->addElement(new \XoopsFormText(\_CO_PUBLISHER_WEIGHT, 'weight', 5, 5, $obj->weight()));
        }

        $this->endTabs();

        //COMMON TO ALL TABS

        $buttonTray = new \XoopsFormElementTray('', '');

        if ($obj->isNew()) {
            $buttonTray->addElement(new \XoopsFormButton('', 'additem', \_CO_PUBLISHER_CREATE, 'submit'));
            $buttonTray->addElement(new \XoopsFormButton('', '', \_CO_PUBLISHER_CLEAR, 'reset'));
        } else {
            $buttonTray->addElement(new \XoopsFormButton('', 'additem', \_SUBMIT, 'submit')); //orclone
        }

        $buttonTray->addElement(new \XoopsFormButton('', 'preview', \_CO_PUBLISHER_PREVIEW, 'submit'));

        $butt_cancel = new \XoopsFormButton('', '', \_CO_PUBLISHER_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $buttonTray->addElement($butt_cancel);

        $this->addElement($buttonTray);

        $hidden = new \XoopsFormHidden('itemid', $obj->itemid());
        $this->addElement($hidden);
        unset($hidden);

        return $this;
    }
}
