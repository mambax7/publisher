#
# Table structure for table `publisher_categories`
#

CREATE TABLE `publisher_categories` (
    `categoryid`       INT(11) NOT NULL AUTO_INCREMENT,
    `parentid`         INT(11) NOT NULL DEFAULT '0',
    `name`             VARCHAR(100) NOT NULL DEFAULT '',
    `description`      TEXT NULL,
    `image`            VARCHAR(255) NOT NULL DEFAULT '',
    `total`            INT(11) NOT NULL DEFAULT '0',
    `weight`           INT(11) NOT NULL DEFAULT '1',
    `created`          INT(11) NOT NULL DEFAULT '1033141070',
    `template`         VARCHAR(255) NOT NULL DEFAULT '',
    `template_item`    VARCHAR(150) NOT NULL DEFAULT '',
    `header`           TEXT NULL,
    `meta_keywords`    TEXT NULL,
    `meta_description` TEXT NULL,
    `short_url`        VARCHAR(255) NOT NULL DEFAULT '',
    `moderator`        INT(6) NOT NULL DEFAULT '0',
    PRIMARY KEY (`categoryid`),
    KEY                parentid (parentid)
) ENGINE = MyISAM;
# --------------------------------------------------------

#
# Table structure for table `publisher_items`
#

CREATE TABLE `publisher_items` (
    `itemid`           INT(11)          NOT NULL AUTO_INCREMENT,
    `categoryid`       INT(11)          NOT NULL DEFAULT '0',
    `title`            VARCHAR(255)     NOT NULL DEFAULT '',
    `subtitle`         VARCHAR(255)     NOT NULL DEFAULT '',
    `summary`          TEXT             NULL,
    `body`             LONGTEXT         NOT NULL,
    `uid`              INT(6)                    DEFAULT '0',
    `author_alias`     VARCHAR(255)     NOT NULL DEFAULT '',
    `datesub`          INT(11)          NOT NULL DEFAULT '0',
    `dateexpire`       INT(11)          NOT NULL DEFAULT '0',
    `status`           INT(1)           NOT NULL DEFAULT '-1',
    `image`            INT(11)          NOT NULL DEFAULT '0',
    `images`           VARCHAR(255)     NOT NULL DEFAULT '',
    `counter`          INT(8) UNSIGNED  NOT NULL DEFAULT '0',
    `rating`           DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
    `votes`            INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `weight`           INT(11)          NOT NULL DEFAULT '1',
    `dohtml`           TINYINT(1)       NOT NULL DEFAULT '1',
    `dosmiley`         TINYINT(1)       NOT NULL DEFAULT '1',
    `doxcode`          TINYINT(1)       NOT NULL DEFAULT '1',
    `doimage`          TINYINT(1)       NOT NULL DEFAULT '1',
    `dobr`             TINYINT(1)       NOT NULL DEFAULT '1',
    `cancomment`       TINYINT(1)       NOT NULL DEFAULT '1',
    `comments`         INT(11)          NOT NULL DEFAULT '0',
    `notifypub`        TINYINT(1)       NOT NULL DEFAULT '0',
    `meta_keywords`    TEXT             NULL,
    `meta_description` TEXT             NULL,
    `short_url`        VARCHAR(255)     NULL,
    `item_tag`         TEXT             NULL,
    `votetype`         TINYINT(1)       NOT NULL DEFAULT '0',
    `votevalue`        TEXT             NULL,
    PRIMARY KEY (`itemid`),
    KEY categoryid (categoryid),
    KEY status (status)
)
    ENGINE = MyISAM;

#
# Table structure for table `publisher_files`
#

CREATE TABLE `publisher_files` (
    `fileid`      INT(11) NOT NULL AUTO_INCREMENT,
    `itemid`      INT(11) NOT NULL DEFAULT '0',
    `name`        VARCHAR(255) NOT NULL DEFAULT '',
    `description` TEXT NULL,
    `filename`    VARCHAR(255) NOT NULL DEFAULT '',
    `mimetype`    VARCHAR(64)  NOT NULL DEFAULT '',
    `uid`         INT(6) DEFAULT '0',
    `datesub`     INT(11) NOT NULL DEFAULT '0',
    `status`      INT(1) NOT NULL DEFAULT '-1',
    `notifypub`   TINYINT(1) NOT NULL DEFAULT '1',
    `counter`     INT(8) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`fileid`)
) ENGINE = MyISAM;

# --------------------------------------------------------


CREATE TABLE `publisher_meta` (
    `metakey`   VARCHAR(50)  NOT NULL DEFAULT '',
    `metavalue` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`metakey`)
) ENGINE = MyISAM;

#
# Dumping data for table `publisher_categories`
#

INSERT INTO `publisher_meta` VALUES ('version', '0.1');
# --------------------------------------------------------

#
# Table structure for table `publisher_mimetypes`
#

CREATE TABLE publisher_mimetypes (
    mime_id    INT(11) NOT NULL AUTO_INCREMENT,
    mime_ext   VARCHAR(60)  NOT NULL DEFAULT '',
    mime_types TEXT NULL,
    mime_name  VARCHAR(255) NOT NULL DEFAULT '',
    mime_admin INT(1) NOT NULL DEFAULT '1',
    mime_user  INT(1) NOT NULL DEFAULT '0',
    KEY        mime_id (mime_id)
) ENGINE = MyISAM;

# --------------------------------------------------------

#
# Table structure for table `publisher_rating`
#

CREATE TABLE `publisher_rating` (
    `ratingid` INT(11) NOT NULL AUTO_INCREMENT,
    `itemid`   INT(11) NOT NULL,
    `uid`      INT(11) NOT NULL,
    `rate`     INT(1) NOT NULL,
    `date`     INT(11) NOT NULL,
    `ip`       VARCHAR(45) NOT NULL DEFAULT '',
    `source`   TINYINT(2) NOT NULL DEFAULT '0',
    `votetype` TINYINT(2) UNSIGNED NOT NULL DEFAULT '1',
    PRIMARY KEY (`ratingid`),
    KEY        uid (uid),
    KEY        ip (ip)
) ENGINE = MyISAM;

# --------------------------------------------------------

#
# Dumping data for table `publisher_mimetypes`
#

INSERT INTO publisher_mimetypes VALUES (null, 'bin', 'application/octet-stream', 'Binary File/Linux Executable', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'dms', 'application/octet-stream', 'Amiga DISKMASHER Compressed Archive', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'class', 'application/octet-stream', 'Java Bytecode', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'so', 'application/octet-stream', 'UNIX Shared Library Function', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'dll', 'application/octet-stream', 'Dynamic Link Library', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'hqx', 'application/binhex application/mac-binhex application/mac-binhex40', 'Macintosh BinHex 4 Compressed Archive', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'cpt', 'application/mac-compactpro application/compact_pro', 'Compact Pro Archive', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'lha', 'application/lha application/x-lha application/octet-stream application/x-compress application/x-compressed application/maclha', 'Compressed Archive File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'lzh', 'application/lzh application/x-lzh application/x-lha application/x-compress application/x-compressed application/x-lzh-archive zz-application/zz-winassoc-lzh application/maclha application/octet-stream', 'Compressed Archive File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'sh', 'application/x-shar', 'UNIX shar Archive File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'shar', 'application/x-shar', 'UNIX shar Archive File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'tar', 'application/tar application/x-tar applicaton/x-gtar multipart/x-tar application/x-compress application/x-compressed', 'Tape Archive File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'gtar', 'application/x-gtar', 'GNU tar Compressed File Archive', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'ustar', 'application/x-ustar multipart/x-ustar', 'POSIX tar Compressed Archive', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'zip', 'application/zip application/x-zip application/x-zip-compressed application/octet-stream application/x-compress application/x-compressed multipart/x-zip', 'Compressed Archive File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'exe', 'application/exe application/x-exe application/dos-exe application/x-winexe application/msdos-windows application/x-msdos-program', 'Executable File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wmz', 'application/x-ms-wmz', 'Windows Media Compressed Skin File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wmd', 'application/x-ms-wmd', 'Windows Media Download File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'doc', 'application/msword application/doc appl/text application/vnd.msword application/vnd.ms-word application/winword application/word application/x-msw6 application/x-msword', 'Word Document', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'pdf', 'application/pdf application/acrobat application/x-pdf applications/vnd.pdf text/pdf', 'Acrobat Portable Document Format', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'eps', 'application/eps application/postscript application/x-eps image/eps image/x-eps', 'Encapsulated PostScript', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'ps', 'application/postscript application/ps application/x-postscript application/x-ps text/postscript', 'PostScript', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'smi', 'application/smil', 'SMIL Multimedia', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'smil', 'application/smil', 'Synchronized Multimedia Integration Language', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wmlc', 'application/vnd.wap.wmlc ', 'Compiled WML Document', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wmlsc', 'application/vnd.wap.wmlscriptc', 'Compiled WML Script', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'vcd', 'application/x-cdlink', 'Virtual CD-ROM CD Image File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'pgn', 'application/formstore', 'Picatinny Arsenal Electronic Formstore Form in TIFF Format', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'cpio', 'application/x-cpio', 'UNIX CPIO Archive', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'csh', 'application/x-csh', 'Csh Script', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'dcr', 'application/x-director', 'Shockwave Movie', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'dir', 'application/x-director', 'Macromedia Director Movie', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'dxr', 'application/x-director application/vnd.dxr', 'Macromedia Director Protected Movie File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'dvi', 'application/x-dvi', 'TeX Device Independent Document', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'spl', 'application/x-futuresplash', 'Macromedia FutureSplash File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'hdf', 'application/x-hdf', 'Hierarchical Data Format File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'js', 'application/x-javascript text/javascript', 'JavaScript Source Code', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'skp', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Play File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'skd', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Design File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'skt', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Template File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'skm', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Mix File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'latex', 'application/x-latex text/x-latex', 'LaTeX Source Document', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'nc', 'application/x-netcdf text/x-cdf', 'Unidata netCDF Graphics', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'cdf', 'application/cdf application/x-cdf application/netcdf application/x-netcdf text/cdf text/x-cdf', 'Channel Definition Format', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'swf', 'application/x-shockwave-flash application/x-shockwave-flash2-preview application/futuresplash image/vnd.rn-realflash', 'Macromedia Flash Format File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'sit', 'application/stuffit application/x-stuffit application/x-sit', 'StuffIt Compressed Archive File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'tcl', 'application/x-tcl', 'TCL/TK Language Script', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'tex', 'application/x-tex', 'LaTeX Source', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'texinfo', 'application/x-texinfo', 'TeX', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'texi', 'application/x-texinfo', 'TeX', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 't', 'application/x-troff', 'TAR Tape Archive Without Compression', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'tr', 'application/x-troff', 'Unix Tape Archive = TAR without compression (tar)', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'src', 'application/x-wais-source', 'Sourcecode', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'xhtml', 'application/xhtml+xml', 'Extensible HyperText Markup Language File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'xht', 'application/xhtml+xml', 'Extensible HyperText Markup Language File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'au', 'audio/basic audio/x-basic audio/au audio/x-au audio/x-pn-au audio/rmf audio/x-rmf audio/x-ulaw audio/vnd.qcelp audio/x-gsm audio/snd', 'ULaw/AU Audio File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'XM', 'audio/xm audio/x-xm audio/module-xm audio/mod audio/x-mod', 'Fast Tracker 2 Extended Module', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'snd', 'audio/basic', 'Macintosh Sound Resource', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'mid', 'audio/mid audio/m audio/midi audio/x-midi application/x-midi audio/soundtrack', 'Musical Instrument Digital Interface MIDI-sequention Sound', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'midi', 'audio/mid audio/m audio/midi audio/x-midi application/x-midi', 'Musical Instrument Digital Interface MIDI-sequention Sound', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'kar', 'audio/midi audio/x-midi audio/mid x-music/x-midi', 'Karaoke MIDI File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'mpga', 'audio/mpeg audio/mp3 audio/mgp audio/m-mpeg audio/x-mp3 audio/x-mpeg audio/x-mpg video/mpeg', 'Mpeg-1 Layer3 Audio Stream', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'mp2', 'video/mpeg audio/mpeg', 'MPEG Audio Stream, Layer II', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'mp3', 'audio/mpeg audio/x-mpeg audio/mp3 audio/x-mp3 audio/mpeg3 audio/x-mpeg3 audio/mpg audio/x-mpg audio/x-mpegaudio', 'MPEG Audio Stream, Layer III', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'aif', 'audio/aiff audio/x-aiff sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/x-midi audio/vnd.qcelp', 'Audio Interchange File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'aiff', 'audio/aiff audio/x-aiff sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/mid audio/x-midi audio/vnd.qcelp', 'Audio Interchange File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'aifc', 'audio/aiff audio/x-aiff audio/x-aifc sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/x-midi audio/mid audio/vnd.qcelp', 'Audio Interchange File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'm3u', 'audio/x-mpegurl audio/mpeg-url application/x-winamp-playlist audio/scpls audio/x-scpls', 'MP3 Playlist File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'ram', 'audio/x-pn-realaudio audio/vnd.rn-realaudio audio/x-pm-realaudio-plugin audio/x-pn-realvideo audio/x-realaudio video/x-pn-realvideo text/plain', 'RealMedia Metafile', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'rm', 'application/vnd.rn-realmedia audio/vnd.rn-realaudio audio/x-pn-realaudio audio/x-realaudio audio/x-pm-realaudio-plugin', 'RealMedia Streaming Media', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'rpm', 'audio/x-pn-realaudio audio/x-pn-realaudio-plugin audio/x-pnrealaudio-plugin video/x-pn-realvideo-plugin audio/x-mpegurl application/octet-stream', 'RealMedia Player Plug-in', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'ra', 'audio/vnd.rn-realaudio audio/x-pn-realaudio audio/x-realaudio audio/x-pm-realaudio-plugin video/x-pn-realvideo', 'RealMedia Streaming Media', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wav', 'audio/wav audio/x-wav audio/wave audio/x-pn-wav', 'Waveform Audio', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wax', ' audio/x-ms-wax', 'Windows Media Audio Redirector', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wma', 'audio/x-ms-wma video/x-ms-asf', 'Windows Media Audio File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'bmp', 'image/bmp image/x-bmp image/x-bitmap image/x-xbitmap image/x-win-bitmap image/x-windows-bmp image/ms-bmp image/x-ms-bmp application/bmp application/x-bmp application/x-win-bitmap application/preview', 'Windows OS/2 Bitmap Graphics', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'gif', 'image/gif image/x-xbitmap image/gi_', 'Graphic Interchange Format', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'ief', 'image/ief', 'Image File - Bitmap graphics', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'jpeg', 'image/jpeg image/jpg image/jpe_ image/pjpeg image/vnd.swiftview-jpeg', 'JPEG/JIFF Image', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'jpg', 'image/jpeg image/jpg image/jp_ application/jpg application/x-jpg image/pjpeg image/pipeg image/vnd.swiftview-jpeg image/x-xbitmap', 'JPEG/JIFF Image', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'jpe', 'image/jpeg', 'JPEG/JIFF Image', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'png', 'image/png application/png application/x-png', 'Portable (Public) Network Graphic', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'tiff', 'image/tiff', 'Tagged Image Format File', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'tif', 'image/tif image/x-tif image/tiff image/x-tiff application/tif application/x-tif application/tiff application/x-tiff', 'Tagged Image Format File', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'ico', 'image/ico image/x-icon application/ico application/x-ico application/x-win-bitmap image/x-win-bitmap application/octet-stream', 'Windows Icon', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wbmp', 'image/vnd.wap.wbmp', 'Wireless Bitmap File Format', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'ras', 'application/ras application/x-ras image/ras', 'Sun Raster Graphic', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'pnm', 'image/x-portable-anymap', 'PBM Portable Any Map Graphic Bitmap', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'pbm', 'image/portable bitmap image/x-portable-bitmap image/pbm image/x-pbm', 'UNIX Portable Bitmap Graphic', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'pgm', 'image/x-portable-graymap image/x-pgm', 'Portable Graymap Graphic', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'ppm', 'image/x-portable-pixmap application/ppm application/x-ppm image/x-p image/x-ppm', 'PBM Portable Pixelmap Graphic', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'rgb', 'image/rgb image/x-rgb', 'Silicon Graphics RGB Bitmap', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'xbm', 'image/x-xpixmap image/x-xbitmap image/xpm image/x-xpm', 'X Bitmap Graphic', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'xpm', 'image/x-xpixmap', 'BMC Software Patrol UNIX Icon File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'xwd', 'image/x-xwindowdump image/xwd image/x-xwd application/xwd application/x-xwd', 'X Windows Dump', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'igs', 'model/iges application/iges application/x-iges application/igs application/x-igs drawing/x-igs image/x-igs', 'Initial Graphics Exchange Specification Format', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'css', 'application/css-stylesheet text/css', 'Hypertext Cascading Style Sheet', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'html', 'text/html text/plain', 'Hypertext Markup Language', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'htm', 'text/html', 'Hypertext Markup Language', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'txt', 'text/plain application/txt browser/internal', 'Text File', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'rtf', 'application/rtf application/x-rtf text/rtf text/richtext application/msword application/doc application/x-soffice', 'Rich Text Format File', 1, 1);
INSERT INTO publisher_mimetypes VALUES (null, 'wml', 'text/vnd.wap.wml text/wml', 'Website META Language File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wmls', 'text/vnd.wap.wmlscript', 'WML Script', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'etx', 'text/x-setext', 'SetText Structure Enhanced Text', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'xml', 'text/xml application/xml application/x-xml', 'Extensible Markup Language File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'xsl', 'text/xml', 'XML Stylesheet', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'php', 'text/php application/x-httpd-php application/php magnus-internal/shellcgi application/x-php', 'PHP Script', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'php3', 'text/php3 application/x-httpd-php', 'PHP Script', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'mpeg', 'video/mpeg', 'MPEG Movie', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'mpg', 'video/mpeg video/mpg video/x-mpg video/mpeg2 application/x-pn-mpg video/x-mpeg video/x-mpeg2a audio/mpeg audio/x-mpeg image/mpg', 'MPEG 1 System Stream', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'mpe', 'video/mpeg', 'MPEG Movie Clip', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'qt', 'video/quicktime audio/aiff audio/x-wav video/flc', 'QuickTime Movie', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'mov', 'video/quicktime video/x-quicktime image/mov audio/aiff audio/x-midi audio/x-wav video/avi', 'QuickTime Video Clip', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'avi', 'video/avi video/msvideo video/x-msvideo image/avi video/xmpg2 application/x-troff-msvideo audio/aiff audio/avi', 'Audio Video Interleave File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'movie', 'video/sgi-movie video/x-sgi-movie', 'QuickTime Movie', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'asf', 'audio/asf application/asx video/x-ms-asf-plugin application/x-mplayer2 video/x-ms-asf application/vnd.ms-asf video/x-ms-asf-plugin video/x-ms-wm video/x-ms-wmx', 'Advanced Streaming Format', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'asx', 'video/asx application/asx video/x-ms-asf-plugin application/x-mplayer2 video/x-ms-asf application/vnd.ms-asf video/x-ms-asf-plugin video/x-ms-wm video/x-ms-wmx video/x-la-asf', 'Advanced Stream Redirector File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wmv', 'video/x-ms-wmv', 'Windows Media File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wvx', 'video/x-ms-wvx', 'Windows Media Redirector', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wm', 'video/x-ms-wm', 'Windows Media A/V File', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'wmx', 'video/x-ms-wmx', 'Windows Media Player A/V Shortcut', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'ice', 'x-conference-xcooltalk', 'Cooltalk Audio', 0, 0);
INSERT INTO publisher_mimetypes VALUES (null, 'rar', 'application/octet-stream', 'WinRAR Compressed Archive', 0, 0);
