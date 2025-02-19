<?php declare(strict_types=1);

namespace XoopsModules\Publisher;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *  Publisher class
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         https://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 */

use Xmf\Request;

require_once \dirname(__DIR__) . '/include/common.php';

/**
 * Class MimetypesUtility
 */
class MimetypesUtility
{
    public static function add(): void
    {
        $helper = Helper::getInstance();
        /** @var MimetypeHandler $mimetypeHandler */
        $mimetypeHandler = $helper->getHandler('Mimetype');
        global $limit, $start;
        $error = [];
        if (Request::getString('add_mime', '', 'POST')) {
            $hasErrors = false;
            $mimeExt   = Request::getString('mime_ext', '', 'POST');
            $mimeName  = Request::getString('mime_name', '', 'POST');
            $mimeTypes = Request::getText('mime_types', '', 'POST');
            $mimeAdmin = Request::getInt('mime_admin', 0, 'POST');
            $mimeUser  = Request::getInt('mime_user', 0, 'POST');

            //Validate Mimetype entry
            if ('' === \trim($mimeExt)) {
                $hasErrors           = true;
                $error['mime_ext'][] = \_AM_PUBLISHER_VALID_ERR_MIME_EXT;
            }

            if ('' === \trim($mimeName)) {
                $hasErrors            = true;
                $error['mime_name'][] = \_AM_PUBLISHER_VALID_ERR_MIME_NAME;
            }

            if ('' === \trim($mimeTypes)) {
                $hasErrors             = true;
                $error['mime_types'][] = \_AM_PUBLISHER_VALID_ERR_MIME_TYPES;
            }

            if ($hasErrors) {
                $session            = Session::getInstance();
                $mime               = [];
                $mime['mime_ext']   = $mimeExt;
                $mime['mime_name']  = $mimeName;
                $mime['mime_types'] = $mimeTypes;
                $mime['mime_admin'] = $mimeAdmin;
                $mime['mime_user']  = $mimeUser;
                $session->set('publisher_addMime', $mime);
                $session->set('publisher_addMimeErr', $error);
                \header('Location: ' . Utility::makeUri(PUBLISHER_ADMIN_URL . '/mimetypes.php', ['op' => 'add'], false));
            }

            $mimeType = $mimetypeHandler->create();
            $mimeType->setVar('mime_ext', $mimeExt);
            $mimeType->setVar('mime_name', $mimeName);
            $mimeType->setVar('mime_types', $mimeTypes);
            $mimeType->setVar('mime_admin', $mimeAdmin);
            $mimeType->setVar('mime_user', $mimeUser);

            if ($mimetypeHandler->insert($mimeType)) {
                self::clearAddSessionVars();
                \header('Location: ' . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=manage&limit=$limit&start=$start");
            } else {
                \redirect_header(PUBLISHER_ADMIN_URL . "/mimetypes.php?op=manage&limit=$limit&start=$start", 3, \_AM_PUBLISHER_MESSAGE_ADD_MIME_ERROR);
            }
        } else {
            Utility::cpHeader();
            //publisher_adminMenu(4, _AM_PUBLISHER_MIMETYPES);

            Utility::openCollapsableBar('mimemaddtable', 'mimeaddicon', \_AM_PUBLISHER_MIME_ADD_TITLE);

            $session    = Session::getInstance();
            $mimeType   = $session->get('publisher_addMime');
            $mimeErrors = $session->get('publisher_addMimeErr');

            //Display any form errors
            if (false === !$mimeErrors) {
                Utility::renderErrors($mimeErrors, Utility::makeUri(PUBLISHER_ADMIN_URL . '/mimetypes.php', ['op' => 'clearAddSession']));
            }

            if (false === $mimeType) {
                $mimeExt   = '';
                $mimeName  = '';
                $mimeTypes = '';
                $mimeAdmin = 1;
                $mimeUser  = 1;
            } else {
                $mimeExt   = $mimeType['mime_ext'];
                $mimeName  = $mimeType['mime_name'];
                $mimeTypes = $mimeType['mime_types'];
                $mimeAdmin = $mimeType['mime_admin'];
                $mimeUser  = $mimeType['mime_user'];
            }

            // Display add form
            echo "<form action='mimetypes.php?op=add' method='post'>";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<table width='100%' cellspacing='1' class='outer'>";
            echo "<tr><th colspan='2'>" . \_AM_PUBLISHER_MIME_CREATEF . '</th></tr>';
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_EXTF . "</td>
        <td class='even'><input type='text' name='mime_ext' id='mime_ext' value='$mimeExt' size='5'></td>
        </tr>";
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_NAMEF . "</td>
        <td class='even'><input type='text' name='mime_name' id='mime_name' value='$mimeName'></td>
        </tr>";
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_TYPEF . "</td>
        <td class='even'><textarea name='mime_types' id='mime_types' cols='60' rows='5'>$mimeTypes</textarea></td>
        </tr>";
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_ADMINF . "</td>
        <td class='even'>";
            echo "<input type='radio' name='mime_admin' value='1' " . (1 == $mimeAdmin ? 'checked' : '') . '>' . \_YES;
            echo "<input type='radio' name='mime_admin' value='0' " . (0 == $mimeAdmin ? 'checked' : '') . '>' . \_NO . '
        </td>
        </tr>';
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_USERF . "</td>
        <td class='even'>";
            echo "<input type='radio' name='mime_user' value='1'" . (1 == $mimeUser ? 'checked' : '') . '>' . \_YES;
            echo "<input type='radio' name='mime_user' value='0'" . (0 == $mimeUser ? 'checked' : '') . '>' . \_NO . '
        </td>
        </tr>';
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_MANDATORY_FIELD . "</td>
        <td class='even'>
        <input type='submit' name='add_mime' id='add_mime' value='" . \_AM_PUBLISHER_BUTTON_SUBMIT . "' class='formButton'>
        <input type='button' name='cancel' value='" . \_AM_PUBLISHER_BUTTON_CANCEL . "' onclick='history.go(-1)' class='formButton'>
        </td>
        </tr>";
            echo '</table></form>';
            // end of add form

            // Find new mimetypes table
            echo "<form action='https://www.filext.com' method='post'>";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<table width='100%' cellspacing='1' class='outer'>";
            echo "<tr><th colspan='2'>" . \_AM_PUBLISHER_MIME_FINDMIMETYPE . '</th></tr>';

            echo "<tr class='foot'>
        <td colspan='2'><input type='submit' name='find_mime' id='find_mime' value='" . \_AM_PUBLISHER_MIME_FINDIT . "' class='formButton'></td>
        </tr>";

            echo '</table></form>';

            Utility::closeCollapsableBar('mimeaddtable', 'mimeaddicon');

            \xoops_cp_footer();
        }
    }

    public static function delete(): void
    {
        $helper = Helper::getInstance();
        /** @var MimetypeHandler $mimetypeHandler */
        $mimetypeHandler = $helper->getHandler('Mimetype');
        global $start, $limit;
        $mimeId = 0;
        if (0 == Request::getInt('id', 0, 'GET')) {
            \redirect_header(PUBLISHER_ADMIN_URL . '/mimetypes.php', 3, \_AM_PUBLISHER_MESSAGE_NO_ID);
        } else {
            $mimeId = Request::getInt('id', 0, 'GET');
        }
        $mimeType = $mimetypeHandler->get($mimeId); // Retrieve mimetype object
        if ($mimetypeHandler->delete($mimeType, true)) {
            \header('Location: ' . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=manage&limit=$limit&start=$start");
        } else {
            \redirect_header(PUBLISHER_ADMIN_URL . "/mimetypes.php?op=manage&id=$mimeId&limit=$limit&start=$start", 3, \_AM_PUBLISHER_MESSAGE_DELETE_MIME_ERROR);
        }
    }

    public static function edit(): void
    {
        $helper = Helper::getInstance();
        /** @var MimetypeHandler $mimetypeHandler */
        $mimetypeHandler = $helper->getHandler('Mimetype');
        global $start, $limit;
        $mimeId    = 0;
        $error     = [];
        $hasErrors = false;
        if (0 == Request::getInt('id', 0, 'GET')) {
            \redirect_header(PUBLISHER_ADMIN_URL . '/mimetypes.php', 3, \_AM_PUBLISHER_MESSAGE_NO_ID);
        } else {
            $mimeId = Request::getInt('id', 0, 'GET');
        }
        $mimeTypeObj = $mimetypeHandler->get($mimeId); // Retrieve mimetype object

        if (Request::getString('edit_mime', '', 'POST')) {
            $mimeAdmin = 0;
            $mimeUser  = 0;
            if (1 == Request::getInt('mime_admin', 0, 'POST')) {
                $mimeAdmin = 1;
            }
            if (1 == Request::getInt('mime_user', 0, 'POST')) {
                $mimeUser = 1;
            }

            //Validate Mimetype entry
            if ('' === Request::getString('mime_ext', '', 'POST')) {
                $hasErrors           = true;
                $error['mime_ext'][] = \_AM_PUBLISHER_VALID_ERR_MIME_EXT;
            }

            if ('' === Request::getString('mime_name', '', 'POST')) {
                $hasErrors            = true;
                $error['mime_name'][] = \_AM_PUBLISHER_VALID_ERR_MIME_NAME;
            }

            if ('' === Request::getString('mime_types', '', 'POST')) {
                $hasErrors             = true;
                $error['mime_types'][] = \_AM_PUBLISHER_VALID_ERR_MIME_TYPES;
            }

            if ($hasErrors) {
                $session            = Session::getInstance();
                $mime               = [];
                $mime['mime_ext']   = Request::getString('mime_ext', '', 'POST');
                $mime['mime_name']  = Request::getString('mime_name', '', 'POST');
                $mime['mime_types'] = Request::getText('mime_types', '', 'POST');
                $mime['mime_admin'] = $mimeAdmin;
                $mime['mime_user']  = $mimeUser;
                $session->set('publisher_editMime_' . $mimeId, $mime);
                $session->set('publisher_editMimeErr_' . $mimeId, $error);
                \header('Location: ' . Utility::makeUri(PUBLISHER_ADMIN_URL . '/mimetypes.php', ['op' => 'edit', 'id' => $mimeId], false));
            }

            $mimeTypeObj->setVar('mime_ext', Request::getString('mime_ext', '', 'POST'));
            $mimeTypeObj->setVar('mime_name', Request::getString('mime_name', '', 'POST'));
            $mimeTypeObj->setVar('mime_types', Request::getText('mime_types', '', 'POST'));
            $mimeTypeObj->setVar('mime_admin', $mimeAdmin);
            $mimeTypeObj->setVar('mime_user', $mimeUser);

            if ($mimetypeHandler->insert($mimeTypeObj, true)) {
                self::clearEditSessionVars($mimeId);
                \header('Location: ' . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=manage&limit=$limit&start=$start");
            } else {
                \redirect_header(PUBLISHER_ADMIN_URL . "/mimetypes.php?op=edit&id=$mimeId", 3, \_AM_PUBLISHER_MESSAGE_EDIT_MIME_ERROR);
            }
        } else {
            $session    = Session::getInstance();
            $mimeType   = $session->get('publisher_editMime_' . $mimeId);
            $mimeErrors = $session->get('publisher_editMimeErr_' . $mimeId);

            // Display header
            Utility::cpHeader();
            //publisher_adminMenu(4, _AM_PUBLISHER_MIMETYPES . " > " . _AM_PUBLISHER_BUTTON_EDIT);

            Utility::openCollapsableBar('mimemedittable', 'mimeediticon', \_AM_PUBLISHER_MIME_EDIT_TITLE);

            //Display any form errors
            if (false === !$mimeErrors) {
                Utility::renderErrors($mimeErrors, Utility::makeUri(PUBLISHER_ADMIN_URL . '/mimetypes.php', ['op' => 'clearEditSession', 'id' => $mimeId]));
            }

            if (false === $mimeType) {
                $mimeExt   = $mimeTypeObj->getVar('mime_ext');
                $mimeName  = $mimeTypeObj->getVar('mime_name', 'e');
                $mimeTypes = $mimeTypeObj->getVar('mime_types', 'e');
                $mimeAdmin = $mimeTypeObj->getVar('mime_admin');
                $mimeUser  = $mimeTypeObj->getVar('mime_user');
            } else {
                $mimeExt   = $mimeType['mime_ext'];
                $mimeName  = $mimeType['mime_name'];
                $mimeTypes = $mimeType['mime_types'];
                $mimeAdmin = $mimeType['mime_admin'];
                $mimeUser  = $mimeType['mime_user'];
            }

            // Display edit form
            echo "<form action='mimetypes.php?op=edit&amp;id=" . $mimeId . "' method='post'>";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<input type='hidden' name='limit' value='" . $limit . "'>";
            echo "<input type='hidden' name='start' value='" . $start . "'>";
            echo "<table width='100%' cellspacing='1' class='outer'>";
            echo "<tr><th colspan='2'>" . \_AM_PUBLISHER_MIME_MODIFYF . '</th></tr>';
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_EXTF . "</td>
        <td class='even'><input type='text' name='mime_ext' id='mime_ext' value='$mimeExt' size='5'></td>
        </tr>";
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_NAMEF . "</td>
        <td class='even'><input type='text' name='mime_name' id='mime_name' value='$mimeName'></td>
        </tr>";
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_TYPEF . "</td>
        <td class='even'><textarea name='mime_types' id='mime_types' cols='60' rows='5'>$mimeTypes</textarea></td>
        </tr>";
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_ADMINF . "</td>
        <td class='even'>
        <input type='radio' name='mime_admin' value='1' " . (1 == $mimeAdmin ? 'checked' : '') . '>' . \_YES . "
        <input type='radio' name='mime_admin' value='0' " . (0 == $mimeAdmin ? 'checked' : '') . '>' . \_NO . '
        </td>
        </tr>';
            echo "<tr valign='top'>
        <td class='head'>" . \_AM_PUBLISHER_MIME_USERF . "</td>
        <td class='even'>
        <input type='radio' name='mime_user' value='1' " . (1 == $mimeUser ? 'checked' : '') . '>' . \_YES . "
        <input type='radio' name='mime_user' value='0' " . (0 == $mimeUser ? 'checked' : '') . '>' . \_NO . '
        </td>
        </tr>';
            echo "<tr valign='top'>
        <td class='head'></td>
        <td class='even'>
        <input type='submit' name='edit_mime' id='edit_mime' value='" . \_AM_PUBLISHER_BUTTON_UPDATE . "' class='formButton'>
        <input type='button' name='cancel' value='" . \_AM_PUBLISHER_BUTTON_CANCEL . "' onclick='history.go(-1)' class='formButton'>
        </td>
        </tr>";
            echo '</table></form>';
            // end of edit form
            Utility::closeCollapsableBar('mimeedittable', 'mimeediticon');
            //            xoops_cp_footer();
            require_once \dirname(__DIR__) . '/admin/admin_footer.php';
        }
    }

    /**
     * @param $icons
     */
    public static function manage($icons): void
    {
        $helper  = Helper::getInstance();
        $utility = new Utility();
        /** @var MimetypeHandler $mimetypeHandler */
        $mimetypeHandler = $helper->getHandler('Mimetype');
        global $start, $limit, $aSortBy, $aOrderBy, $aLimitBy, $aSearchBy;

        if (Request::getString('deleteMimes', '', 'POST')) {
            $aMimes = Request::getArray('mimes', [], 'POST');

            $crit = new \Criteria('mime_id', '(' . \implode(',', $aMimes) . ')', 'IN');

            if ($mimetypeHandler->deleteAll($crit)) {
                \header('Location: ' . PUBLISHER_ADMIN_URL . "/mimetypes.php?limit=$limit&start=$start");
            } else {
                \redirect_header(PUBLISHER_ADMIN_URL . "/mimetypes.php?limit=$limit&start=$start", 3, \_AM_PUBLISHER_MESSAGE_DELETE_MIME_ERROR);
            }
        }
        if (Request::getString('add_mime', '', 'POST')) {
            //        header("Location: " . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=add&start=$start&limit=$limit");
            \redirect_header(PUBLISHER_ADMIN_URL . "/mimetypes.php?op=add&start=$start&limit=$limit", 3, \_AM_PUBLISHER_MIME_CREATEF);
        }
        if (Request::getString('mime_search', '', 'POST')) {
            //        header("Location: " . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=search");
            \redirect_header(PUBLISHER_ADMIN_URL . '/mimetypes.php?op=search', 3, \_AM_PUBLISHER_MIME_SEARCH);
        }

        Utility::cpHeader();
        ////publisher_adminMenu(4, _AM_PUBLISHER_MIMETYPES);
        Utility::openCollapsableBar('mimemanagetable', 'mimemanageicon', \_AM_PUBLISHER_MIME_MANAGE_TITLE, \_AM_PUBLISHER_MIME_INFOTEXT);
        $crit  = new \CriteriaCompo();
        $order = Request::getString('order', 'ASC', 'POST');
        $sort  = Request::getString('sort', 'mime_ext', 'POST');

        $crit->setOrder($order);
        $crit->setStart($start);
        $crit->setLimit($limit);
        $crit->setSort($sort);
        $mimetypes = $mimetypeHandler->getObjects($crit); // Retrieve a list of all mimetypes
        $mimeCount = $mimetypeHandler->getCount();
        $nav       = new \XoopsPageNav($mimeCount, $limit, $start, 'start', "op=manage&amp;limit=$limit");

        echo "<table width='100%' cellspacing='1' class='outer'>";
        echo "<tr><td colspan='6' align='right'>";
        echo "<form action='" . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=search' style='margin:0; padding:0;' method='post'>";
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo '<table>';
        echo '<tr>';
        echo "<td align='right'>" . \_AM_PUBLISHER_TEXT_SEARCH_BY . '</td>';
        echo "<td align='left'><select name='search_by'>";
        foreach ($aSearchBy as $value => $text) {
            ($sort == $value) ? $selected = 'selected' : $selected = '';
            echo "<option value='$value' $selected>$text</option>";
        }
        unset($value);
        echo '</select></td>';
        echo "<td align='right'>" . \_AM_PUBLISHER_TEXT_SEARCH_TEXT . '</td>';
        echo "<td align='left'><input type='text' name='search_text' id='search_text' value=''></td>";
        echo "<td><input type='submit' name='mime_search' id='mime_search' value='" . \_AM_PUBLISHER_BUTTON_SEARCH . "'></td>";
        echo '</tr></table></form></td></tr>';

        echo "<tr><td colspan='6'>";
        echo "<form action='" . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=manage' style='margin:0; padding:0;' method='post'>";
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo "<table width='100%'>";
        echo "<tr><td align='right'>" . \_AM_PUBLISHER_TEXT_SORT_BY . "
    <select name='sort'>";
        foreach ($aSortBy as $value => $text) {
            ($sort == $value) ? $selected = 'selected' : $selected = '';
            echo "<option value='$value' $selected>$text</option>";
        }
        unset($value);
        echo '</select>
    &nbsp;&nbsp;&nbsp;
    ' . \_AM_PUBLISHER_TEXT_ORDER_BY . "
    <select name='order'>";
        foreach ($aOrderBy as $value => $text) {
            ($order == $value) ? $selected = 'selected' : $selected = '';
            echo "<option value='$value' $selected>$text</option>";
        }
        unset($value);
        echo '</select>
    &nbsp;&nbsp;&nbsp;
    ' . \_AM_PUBLISHER_TEXT_NUMBER_PER_PAGE . "
    <select name='limit'>";
        foreach ($aLimitBy as $value => $text) {
            ($limit == $value) ? $selected = 'selected' : $selected = '';
            echo "<option value='$value' $selected>$text</option>";
        }
        unset($value);
        echo "</select>
    <input type='submit' name='mime_sort' id='mime_sort' value='" . \_AM_PUBLISHER_BUTTON_SUBMIT . "'>
    </td>
    </tr>";
        echo '</table>';
        echo '</td></tr>';
        echo "<tr><th colspan='6'>" . \_AM_PUBLISHER_MIME_MANAGE_TITLE . '</th></tr>';
        echo "<tr class='head'>
    <td>" . \_AM_PUBLISHER_MIME_ID . '</td>
    <td>' . \_AM_PUBLISHER_MIME_NAME . "</td>
    <td align='center'>" . \_AM_PUBLISHER_MIME_EXT . "</td>
    <td align='center'>" . \_AM_PUBLISHER_MIME_ADMIN . "</td>
    <td align='center'>" . \_AM_PUBLISHER_MIME_USER . "</td>
    <td align='center'>" . \_AM_PUBLISHER_MINDEX_ACTION . '</td>
    </tr>';
        foreach ($mimetypes as $mime) {
            echo "<tr class='even'>
        <td><input type='checkbox' name='mimes[]' value='" . $mime->getVar('mime_id') . "'>" . $mime->getVar('mime_id') . '</td>
        <td>' . $mime->getVar('mime_name') . "</td>
        <td align='center'>" . $mime->getVar('mime_ext') . "</td>
        <td align='center'>
        <a href='" . PUBLISHER_ADMIN_URL . '/mimetypes.php?op=updateMimeValue&amp;id=' . $mime->getVar('mime_id') . '&amp;mime_admin=' . $mime->getVar('mime_admin') . '&amp;limit=' . $limit . '&amp;start=' . $start . "'>
        " . ($mime->getVar('mime_admin') ? $icons['online'] : $icons['offline']) . "</a>
        </td>
        <td align='center'>
        <a href='" . PUBLISHER_ADMIN_URL . '/mimetypes.php?op=updateMimeValue&amp;id=' . $mime->getVar('mime_id') . '&amp;mime_user=' . $mime->getVar('mime_user') . '&amp;limit=' . $limit . '&amp;start=' . $start . "'>
        " . ($mime->getVar('mime_user') ? $icons['online'] : $icons['offline']) . "</a>
        </td>
        <td align='center'>
        <a href='" . PUBLISHER_ADMIN_URL . '/mimetypes.php?op=edit&amp;id=' . $mime->getVar('mime_id') . '&amp;limit=' . $limit . '&amp;start=' . $start . "'>" . $icons['edit'] . "</a>
        <a href='" . PUBLISHER_ADMIN_URL . '/mimetypes.php?op=delete&amp;id=' . $mime->getVar('mime_id') . '&amp;limit=' . $limit . '&amp;start=' . $start . "'>" . $icons['delete'] . '</a>
        </td>
        </tr>';
        }
        //        unset($mime);
        echo "<tr class='foot'>
    <td colspan='6' valign='top'>
    <a href='https://www.filext.com' style='float: right;' target='_blank'>" . \_AM_PUBLISHER_MIME_FINDMIMETYPE . "</a>
    <input type='checkbox' name='checkAllMimes' value='0' onclick='selectAll(this.form,\"mimes[]\",this.checked);'>
    <input type='submit' name='deleteMimes' id='deleteMimes' value='" . \_AM_PUBLISHER_BUTTON_DELETE . "'>
    <input type='submit' name='add_mime' id='add_mime' value='" . \_AM_PUBLISHER_MIME_CREATEF . "' class='formButton'>
    </td>
    </tr>";
        echo '</table>';
        echo "<div id='staff_nav'>" . $nav->renderNav() . '</div><br>';

        Utility::closeCollapsableBar('mimemanagetable', 'mimemanageicon');

        //        xoops_cp_footer();
        require_once \dirname(__DIR__) . '/admin/admin_footer.php';
    }

    /**
     * @param array $icons
     */
    public static function search($icons): void
    {
        $helper = Helper::getInstance();
        /** @var MimetypeHandler $mimetypeHandler */
        $mimetypeHandler = $helper->getHandler('Mimetype');
        global $limit, $start, $aSearchBy, $aOrderBy, $aLimitBy, $aSortBy;

        if (Request::getString('deleteMimes', '', 'POST')) {
            $aMimes = Request::getArray('mimes', [], 'POST');

            $crit = new \Criteria('mime_id', '(' . \implode(',', $aMimes) . ')', 'IN');

            if ($mimetypeHandler->deleteAll($crit)) {
                \header('Location: ' . PUBLISHER_ADMIN_URL . "/mimetypes.php?limit=$limit&start=$start");
            } else {
                \redirect_header(PUBLISHER_ADMIN_URL . "/mimetypes.php?limit=$limit&start=$start", 3, \_AM_PUBLISHER_MESSAGE_DELETE_MIME_ERROR);
            }
        }
        if (Request::getString('add_mime', '', 'POST')) {
            //        header("Location: " . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=add&start=$start&limit=$limit");
            \redirect_header(PUBLISHER_ADMIN_URL . "/mimetypes.php?op=add&start=$start&limit=$limit", 3, \_AM_PUBLISHER_MIME_CREATEF);
        }

        $order = Request::getString('order', 'ASC');
        $sort  = Request::getString('sort', 'mime_name');

        Utility::cpHeader();
        //publisher_adminMenu(4, _AM_PUBLISHER_MIMETYPES . " > " . _AM_PUBLISHER_BUTTON_SEARCH);

        Utility::openCollapsableBar('mimemsearchtable', 'mimesearchicon', \_AM_PUBLISHER_MIME_SEARCH);

        if (Request::hasVar('mime_search')) {
            $searchField = Request::getString('search_by', '');
            $searchField = isset($aSearchBy[$searchField]) ? $searchField : 'mime_ext';
            $searchText  = Request::getString('search_text', '');

            $crit = new \Criteria($searchField, '%' . $GLOBALS['xoopsDB']->escape($searchText) . '%', 'LIKE');
            $crit->setSort($sort);
            $crit->setOrder($order);
            $crit->setLimit($limit);
            $crit->setStart($start);
            $mimeCount = $mimetypeHandler->getCount($crit);
            $mimetypes = $mimetypeHandler->getObjects($crit);
            $nav       = new \XoopsPageNav($mimeCount, $limit, $start, 'start', "op=search&amp;limit=$limit&amp;order=$order&amp;sort=$sort&amp;mime_search=1&amp;search_by=$searchField&amp;search_text=" . \htmlentities($searchText, \ENT_QUOTES | ENT_HTML5));
            // Display results
            echo '<script type="text/javascript" src="' . PUBLISHER_URL . '/include/functions.js"></script>';

            echo "<table width='100%' cellspacing='1' class='outer'>";
            echo "<tr><td colspan='6' align='right'>";
            echo "<form action='" . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=search' style='margin:0; padding:0;' method='post'>";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo '<table>';
            echo '<tr>';
            echo "<td align='right'>" . \_AM_PUBLISHER_TEXT_SEARCH_BY . '</td>';
            echo "<td align='left'><select name='search_by'>";
            foreach ($aSearchBy as $value => $text) {
                ($searchField == $value) ? $selected = 'selected' : $selected = '';
                echo "<option value='$value' $selected>$text</option>";
            }
            unset($value);
            echo '</select></td>';
            echo "<td align='right'>" . \_AM_PUBLISHER_TEXT_SEARCH_TEXT . '</td>';
            echo "<td align='left'><input type='text' name='search_text' id='search_text' value='" . \htmlentities($searchText, \ENT_QUOTES | ENT_HTML5) . "'></td>";
            echo "<td><input type='submit' name='mime_search' id='mime_search' value='" . \_AM_PUBLISHER_BUTTON_SEARCH . "'></td>";
            echo '</tr></table></form></td></tr>';

            echo "<tr><td colspan='6'>";
            echo "<form action='" . PUBLISHER_ADMIN_URL . "/mimetypes.php?op=search' style='margin:0; padding:0;' method='post'>";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<table width='100%'>";
            echo "<tr><td align='right'>" . \_AM_PUBLISHER_TEXT_SORT_BY . "
        <select name='sort'>";
            foreach ($aSortBy as $value => $text) {
                ($sort == $value) ? $selected = 'selected' : $selected = '';
                echo "<option value='$value' $selected>$text</option>";
            }
            unset($value);
            echo '</select>
        &nbsp;&nbsp;&nbsp;
        ' . \_AM_PUBLISHER_TEXT_ORDER_BY . "
        <select name='order'>";
            foreach ($aOrderBy as $value => $text) {
                ($order == $value) ? $selected = 'selected' : $selected = '';
                echo "<option value='$value' $selected>$text</option>";
            }
            unset($value);
            echo '</select>
        &nbsp;&nbsp;&nbsp;
        ' . \_AM_PUBLISHER_TEXT_NUMBER_PER_PAGE . "
        <select name='limit'>";
            foreach ($aLimitBy as $value => $text) {
                ($limit == $value) ? $selected = 'selected' : $selected = '';
                echo "<option value='$value' $selected>$text</option>";
            }
            unset($value);
            echo "</select>
        <input type='submit' name='mime_sort' id='mime_sort' value='" . \_AM_PUBLISHER_BUTTON_SUBMIT . "'>
        <input type='hidden' name='mime_search' id='mime_search' value='1'>
        <input type='hidden' name='search_by' id='search_by' value='$searchField'>
        <input type='hidden' name='search_text' id='search_text' value='" . \htmlentities($searchText, \ENT_QUOTES | ENT_HTML5) . "'>
        </td>
        </tr>";
            echo '</table>';
            echo '</td></tr>';
            if (\count($mimetypes) > 0) {
                echo "<tr><th colspan='6'>" . \_AM_PUBLISHER_TEXT_SEARCH_MIME . '</th></tr>';
                echo "<tr class='head'>
            <td>" . \_AM_PUBLISHER_MIME_ID . '</td>
            <td>' . \_AM_PUBLISHER_MIME_NAME . "</td>
            <td align='center'>" . \_AM_PUBLISHER_MIME_EXT . "</td>
            <td align='center'>" . \_AM_PUBLISHER_MIME_ADMIN . "</td>
            <td align='center'>" . \_AM_PUBLISHER_MIME_USER . "</td>
            <td align='center'>" . \_AM_PUBLISHER_MINDEX_ACTION . '</td>
            </tr>';
                foreach ($mimetypes as $mime) {
                    echo "<tr class='even'>
                <td><input type='checkbox' name='mimes[]' value='" . $mime->getVar('mime_id') . "'>" . $mime->getVar('mime_id') . '</td>
                <td>' . $mime->getVar('mime_name') . "</td>
                <td align='center'>" . $mime->getVar('mime_ext') . "</td>
                <td align='center'>
                <a href='" . PUBLISHER_ADMIN_URL . '/mimetypes.php?op=updateMimeValue&amp;id=' . $mime->getVar('mime_id') . '&amp;mime_admin=' . $mime->getVar('mime_admin') . '&amp;limit=' . $limit . '&amp;start=' . $start . "'>
                " . ($mime->getVar('mime_admin') ? $icons['online'] : $icons['offline']) . "</a>
                </td>
                <td align='center'>
                <a href='" . PUBLISHER_ADMIN_URL . '/mimetypes.php?op=updateMimeValue&amp;id=' . $mime->getVar('mime_id') . '&amp;mime_user=' . $mime->getVar('mime_user') . '&amp;limit=' . $limit . '&amp;start=' . $start . "'>
                " . ($mime->getVar('mime_user') ? $icons['online'] : $icons['offline']) . "</a>
                </td>
                <td align='center'>
                <a href='" . PUBLISHER_ADMIN_URL . '/mimetypes.php?op=edit&amp;id=' . $mime->getVar('mime_id') . '&amp;limit=' . $limit . '&amp;start=' . $start . "'>" . $icons['edit'] . "</a>
                <a href='" . PUBLISHER_ADMIN_URL . '/mimetypes.php?op=delete&amp;id=' . $mime->getVar('mime_id') . '&amp;limit=' . $limit . '&amp;start=' . $start . "'>" . $icons['delete'] . '</a>
                </td>
                </tr>';
                }
                //                unset($mime);
                echo "<tr class='foot'>
            <td colspan='6' valign='top'>
            <a href='https://www.filext.com' style='float: right;' target='_blank'>" . \_AM_PUBLISHER_MIME_FINDMIMETYPE . "</a>
            <input type='checkbox' name='checkAllMimes' value='0' onclick='selectAll(this.form,\"mimes[]\",this.checked);'>
            <input type='submit' name='deleteMimes' id='deleteMimes' value='" . \_AM_PUBLISHER_BUTTON_DELETE . "'>
            <input type='submit' name='add_mime' id='add_mime' value='" . \_AM_PUBLISHER_MIME_CREATEF . "' class='formButton'>
            </td>
            </tr>";
            } else {
                echo '<tr><th>' . \_AM_PUBLISHER_TEXT_SEARCH_MIME . '</th></tr>';
                echo "<tr class='even'>
            <td>" . \_AM_PUBLISHER_TEXT_NO_RECORDS . '</td>
            </tr>';
            }
            echo '</table>';
            echo "<div id='pagenav'>" . $nav->renderNav() . '</div>';
        } else {
            echo "<form action='mimetypes.php?op=search' method='post'>";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<table width='100%' cellspacing='1' class='outer'>";
            echo "<tr><th colspan='2'>" . \_AM_PUBLISHER_TEXT_SEARCH_MIME . '</th></tr>';
            echo "<tr><td class='head' width='20%'>" . \_AM_PUBLISHER_TEXT_SEARCH_BY . "</td>
        <td class='even'>
        <select name='search_by'>";
            foreach ($aSortBy as $value => $text) {
                echo "<option value='$value'>$text</option>";
            }
            unset($value);
            echo '</select>
        </td>
        </tr>';
            echo "<tr><td class='head'>" . \_AM_PUBLISHER_TEXT_SEARCH_TEXT . "</td>
        <td class='even'>
        <input type='text' name='search_text' id='search_text' value=''>
        </td>
        </tr>";
            echo "<tr class='foot'>
        <td colspan='2'>
        <input type='submit' name='mime_search' id='mime_search' value='" . \_AM_PUBLISHER_BUTTON_SEARCH . "'>
        </td>
        </tr>";
            echo '</table></form>';
        }
        Utility::closeCollapsableBar('mimesearchtable', 'mimesearchicon');
        //        require_once \dirname(__DIR__) . '/admin/admin_footer.php';
        \xoops_cp_footer();
    }

    /**
     * confirm update to mime access, resubmit as POST, including TOKEN
     */
    public static function updateMimeValue(): void
    {
        // op=updateMimeValue&id=65&mime_admin=0&limit=15&start=0
        Utility::cpHeader();
        $hiddens = [
            'id'    => Request::getInt('id', 0, 'GET'),
            'start' => Request::getInt('start', 0, 'GET'),
            'limit' => Request::getInt('limit', 15, 'GET'),
        ];

        $helper = Helper::getInstance();
        /** @var MimetypeHandler $mimetypeHandler */
        $mimeTypeObj = $helper->getHandler('Mimetype')
                              ->get($hiddens['id']);
        if (Request::hasVar('mime_admin')) {
            $hiddens['mime_admin'] = Request::getInt('mime_admin', 0, 'GET');
            $msg                   = \sprintf(\_AM_PUBLISHER_MIME_ACCESS_CONFIRM_ADMIN, $mimeTypeObj->getVar('mime_name'));
        } else {
            $hiddens['mime_user'] = Request::getInt('mime_user', 0, 'GET');
            $msg                  = \sprintf(\_AM_PUBLISHER_MIME_ACCESS_CONFIRM_USER, $mimeTypeObj->getVar('mime_name'));
        }

        $action = PUBLISHER_ADMIN_URL . '/mimetypes.php?op=confirmUpdateMimeValue';
        $submit = \_AM_PUBLISHER_MIME_ACCESS_CONFIRM;

        \xoops_confirm($hiddens, $action, $msg, $submit, true);
        \xoops_cp_footer();
    }

    public static function confirmUpdateMimeValue(): void
    {
        $helper = Helper::getInstance();
        /** @var MimetypeHandler $mimetypeHandler */
        $mimetypeHandler = $helper->getHandler('Mimetype');
        $limit           = Request::getInt('limit', 0, 'POST');
        $start           = Request::getInt('start', 0, 'POST');
        $mimeId          = Request::getInt('id', 0, 'POST');
        if (0 === $mimeId) {
            \redirect_header(PUBLISHER_ADMIN_URL . '/mimetypes.php', 3, \_AM_PUBLISHER_MESSAGE_NO_ID);
        }

        $mimeTypeObj = $mimetypeHandler->get($mimeId);

        if (-1 !== ($mimeAdmin = Request::getInt('mime_admin', -1, 'POST'))) {
            $mimeAdmin = self::changeMimeValue($mimeAdmin);
            $mimeTypeObj->setVar('mime_admin', $mimeAdmin);
        } elseif (-1 !== ($mimeUser = Request::getInt('mime_user', -1, 'POST'))) {
            $mimeUser = self::changeMimeValue($mimeUser);
            $mimeTypeObj->setVar('mime_user', $mimeUser);
        }
        if ($mimetypeHandler->insert($mimeTypeObj, true)) {
            \header('Location: ' . PUBLISHER_ADMIN_URL . "/mimetypes.php?limit=$limit&start=$start");
        } else {
            \redirect_header(PUBLISHER_ADMIN_URL . "/mimetypes.php?limit=$limit&start=$start", 3);
        }
    }

    /**
     * @param $mimeValue
     *
     * @return int
     */
    protected static function changeMimeValue($mimeValue)
    {
        if (1 === (int)$mimeValue) {
            $mimeValue = 0;
        } else {
            $mimeValue = 1;
        }

        return $mimeValue;
    }

    protected static function clearAddSessionVars(): void
    {
        $session = Session::getInstance();
        $session->del('publisher_addMime');
        $session->del('publisher_addMimeErr');
    }

    public static function clearAddSession(): void
    {
        self::clearAddSessionVars();
        \header('Location: ' . Utility::makeUri(PUBLISHER_ADMIN_URL . '/mimetypes.php', ['op' => 'add'], false));
    }

    /**
     * @param int $id
     */
    public static function clearEditSessionVars($id): void
    {
        $id      = (int)$id;
        $session = Session::getInstance();
        $session->del("publisher_editMime_$id");
        $session->del("publisher_editMimeErr_$id");
    }

    public static function clearEditSession(): void
    {
        $mimeid = Request::getInt('id', '', 'GET');
        self::clearEditSessionVars($mimeid);
        \header('Location: ' . Utility::makeUri(PUBLISHER_ADMIN_URL . '/mimetypes.php', ['op' => 'edit', 'id' => $mimeid], false));
    }
}
