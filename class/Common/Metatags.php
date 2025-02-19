<?php declare(strict_types=1);

namespace XoopsModules\Publisher\Common;

/**
 * Class META TAGS
 *
 * @author Salih Andıç
 * @web https://www.salihandic.com/
 * @mail salihandic@outlook.com
 * @date   20 November 2018
 */
final class Meta
{
    /**
     * @param string $localeCode
     * @return string
     */
    public static function getStatik($localeCode)
    {
        return '
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" >
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="index,follow">
        <meta name="revisit-after" content="1 days">
        <meta name="referrer" content="origin-when-cross-origin">
        <meta name="locale" content="' . $localeCode . '">';
    }

    /**
     * @return string
     */
    public static function getRobot()
    {
        return '
        <meta name="robots" content="all">
        <meta name="googlebot" content="snippet">
        <meta name="googlebot" content="index, follow">
        <meta name="robots" content="index, follow">';
    }

    /**
     * @return string
     */
    public static function getNorobot()
    {
        return '
        <meta name="googlebot" content="noindex, nofollow">
        <meta name="robots" content="noindex, nofollow">';
    }

    /**
     * @param $title
     * @return string
     */
    public static function getTitle($title)
    {
        return '<title>' . $title . '</title>';
    }

    /**
     * @param $desc
     * @return string
     */
    public static function getDescription($desc)
    {
        return '<meta itemprop="description" name="description" content="' . $desc . '">';
    }

    /**
     * @param $langList
     * @return string
     */
    public static function getAlternate($langList)
    {
        $alternateLangList = '';

        if (\count($langList) > 1) :
            foreach ($langList as $lang) :
                $alternateLangList .= '
                <link rel="alternate" hreflang="' . $lang['hreflang'] . '" href="' . home('?lang=' . $lang['code']) . '">';
            endforeach;
        else :
            $alternateLangList = '
            <link rel="alternate" hreflang="' . $lang['hreflang'] . '" href="' . home('?lang=' . $lang['code']) . '">';

        endif;

        return $alternateLangList;
    }

    /**
     * @param $fb
     * @return string
     */
    public static function getFacebook($fb)
    {
        $fbh = '';

        if (\is_array($fb)) :
            foreach ($fb as $fbkey => $fbrow) :
                $fbh .= '
                <meta property="og:' . $fbkey . '" content="' . $fbrow . '">';

            endforeach;

        endif;

        return $fbh;
    }

    /**
     * @param $tw
     * @return string
     */
    public static function getTwitter($tw)
    {
        $twh = '';

        if (\is_array($tw)) :
            foreach ($tw as $twkey => $twrow) :
                $twh .= '<meta name="twitter:' . $twkey . '" content="' . $twrow . '">';

            endforeach;

        endif;

        return $twh;
    }

    /**
     * @param $icon
     * @return string
     */
    public static function getIcon($icon)
    {
        $iconh = '';

        if (\is_array($icon)) :
            foreach ($icon as $iconkey => $iconrow) :
                $iconh .= '<meta name="' . $iconkey . '" href="' . $iconrow . '">';

            endforeach;

        endif;

        return $iconh;
    }

    /**
     * @param $author
     * @return string
     */
    public static function getAuthor($author)
    {
        return '<meta name="author" itemprop="author" content="' . $author . '">';
    }

    /**
     * @param $canonical
     * @return string
     */
    public static function getCanonical($canonical)
    {
        return '<link rel="canonical" itemprop="url" type="text/html" href="' . $canonical . '">';
    }

    /**
     * @param $manifest
     * @return string
     */
    public static function getManifest($manifest)
    {
        return '<link rel="manifest" href="' . $manifest . '">';
    }

    /**
     * @param $google
     * @return string
     */
    public static function getGoogle($google)
    {
        return '<meta name="google-site-verification" content="' . $google . '">';
    }

    /**
     * @param $bing
     * @return string
     */
    public static function getBing($bing)
    {
        return '
        <meta name="msvalidate.01" content="' . $bing . '">';
    }

    /**
     * @param $yandex
     * @return string
     */
    public static function getgetYandex($yandex)
    {
        return '<meta name="yandex-verification" content="' . $yandex . '">';
    }

    /**
     * @param $amp
     * @return string
     */
    public static function getAmp($amp)
    {
        return '<meta rel="amphtml" content="' . $amp . '">';
    }

    /**
     * @param $crumb
     * @return string
     */
    public static function getBreadcrumb($crumb)
    {
        $h = '';

        $count = 0;

        $bcount = \count($crumb);

        if (\is_array($crumb)) :
            $h .= '<script type="application/ld+json">{
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement":[';

            foreach ($crumb as $crumbrow) :
                ++$count;

                $h .= '
                    {
                        "@type": "ListItem",
                        "position":"' . $crumbrow['position'] . '",
                        "item": {
                            "@id":"' . $crumbrow['id'] . '",
                            "name": "' . $crumbrow['name'] . '"
                        }
                    }';

                $h .= $count == $bcount ? '' : ',';

            endforeach;

            $h .= ']}
       </script>';

        endif;

        return $h;
    }
}

/*
How to use?
======================
echo Meta:Twitter([
                      "site" => "Başlık",
                      "creator" => "@salihandic",
                      "via" => "@salihandic",
                      "card" => "summary_large_image",
                      "url" => "https://twitter.com/salihandic",
                      "description" => "",
                      "image" => "",
                      "image:width" => "640",
                      "image:height" => "640",
                      "image:alt" => "Salih Andıç",
                      "domain" => "twitter.com"
                  ]);
Output;

<meta name="twitter:title" content="Başlık">
<meta name="twitter:site" content="@salihandic">
<meta name="twitter:creator" content="@salihandic">
<meta name="twitter:via" content="salihandic">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="https://www.twitter.com">
<meta name="twitter:description" content="">
<meta name="twitter:image" content="">
<meta name="twitter:image:width" content="640">
<namemeta= "twitter:image:height" content="640">
<meta name="twitter:image:alt" content="Salih Andıç">
<meta name="twitter:domain" content="twitter.com">

*/
