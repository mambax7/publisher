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
 * @author          Sudhaker Raj <https://xoops.biz>
 */

use Xmf\Request;

//$seoOp = @$_GET['seoOp'];
$seoOp = Request::getString('seoOp', '', 'GET');

//$seoArg = @$_GET['seoArg'];
$seoArg = Request::getString('seoArg', '', 'GET');

if ('' == $seoOp && Request::getString('PATH_INFO', '', 'SERVER')) {
    // SEO mode is path-info
    /*
    Sample URL for path-info
    http://localhost/modules/publisher/seo.php/item.2/can-i-turn-the-ads-off.html
    */
    $data = explode('/', Request::getString('PATH_INFO', '', 'SERVER'));

    $seoParts = explode('.', $data[1]);
    $seoOp    = $seoParts[0];
    $seoArg   = $seoParts[1];
    // for multi-argument modules, where itemid and catid both are required.
    // $seoArg = mb_substr($data[1], mb_strlen($seoOp) + 1);
}

$seoMap = [
    'category' => 'category.php',
    'item'     => 'item.php',
    'print'    => 'print.php',
];

if (!empty($seoOp) && isset($seoMap[$seoOp])) {
    // module specific dispatching logic, other module must implement as
    // per their requirements.

    $url_arr = explode('/modules/', Request::getString('SCRIPT_NAME', '', 'SERVER'));
    $newUrl  = $url_arr[0] . '/modules/' . PUBLISHER_DIRNAME . '/' . $seoMap[$seoOp];

    $_ENV['SCRIPT_NAME']    = $newUrl;
    $_SERVER['SCRIPT_NAME'] = $newUrl;
    switch ($seoOp) {
        case 'category':
            $_SERVER['REQUEST_URI'] = $newUrl . '?categoryid=' . $seoArg;
            $_GET['categoryid']     = $seoArg;
            $_REQUEST['categoryid'] = $seoArg;
            break;
        case 'item':
        case 'print':
        default:
            $_SERVER['REQUEST_URI'] = $newUrl . '?itemid=' . $seoArg;
            $_GET['itemid']         = $seoArg;
            $_REQUEST['itemid']     = $seoArg;
    }
    require PUBLISHER_ROOT_PATH . '/' . $seoMap[$seoOp];
    exit;
}
