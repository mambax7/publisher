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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         https://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

/**
 * interface Constants
 */
interface Constants
{
    // ITEM status
    public const PUBLISHER_STATUS_NOTSET    = -1;
    public const PUBLISHER_STATUS_ALL       = 0;
    public const PUBLISHER_STATUS_SUBMITTED = 1;
    public const PUBLISHER_STATUS_PUBLISHED = 2;
    public const PUBLISHER_STATUS_OFFLINE   = 3;
    public const PUBLISHER_STATUS_REJECTED  = 4;
    // Notification Events
    public const PUBLISHER_NOT_CATEGORY_CREATED  = 1;
    public const PUBLISHER_NOTIFY_ITEM_SUBMITTED = 2;
    public const PUBLISHER_NOTIFY_ITEM_PUBLISHED = 3;
    public const PUBLISHER_NOTIFY_ITEM_REJECTED  = 4;
    // Form constants
    public const PUBLISHER_SUMMARY = 1;
    //const PUBLISHER_DISPLAY_SUMMARY = 2;
    public const PUBLISHER_AVAILABLE_PAGE_WRAP = 3;
    public const PUBLISHER_ITEM_TAG            = 4;
    public const PUBLISHER_IMAGE_ITEM          = 5;
    //const PUBLISHER_IMAGE_UPLOAD = 6;
    public const PUBLISHER_ITEM_UPLOAD_FILE      = 7;
    public const PUBLISHER_UID                   = 8;
    public const PUBLISHER_DATESUB               = 9;
    public const PUBLISHER_STATUS                = 10;
    public const PUBLISHER_ITEM_SHORT_URL        = 11;
    public const PUBLISHER_ITEM_META_KEYWORDS    = 12;
    public const PUBLISHER_ITEM_META_DESCRIPTION = 13;
    public const PUBLISHER_WEIGHT                = 14;
    public const PUBLISHER_ALLOWCOMMENTS         = 15;
    //const PUBLISHER_PERMISSIONS_ITEM = 16;
    //const PUBLISHER_PARTIAL_VIEW = 17;
    public const PUBLISHER_DOHTML       = 18;
    public const PUBLISHER_DOSMILEY     = 19;
    public const PUBLISHER_DOXCODE      = 20;
    public const PUBLISHER_DOIMAGE      = 21;
    public const PUBLISHER_DOLINEBREAK  = 22;
    public const PUBLISHER_NOTIFY       = 23;
    public const PUBLISHER_SUBTITLE     = 24;
    public const PUBLISHER_AUTHOR_ALIAS = 25;
    public const PUBLISHER_DATEEXPIRE   = 26;
    public const PUBLISHER_VOTETYPE     = 27;
    // Global constants
    public const PUBLISHER_SEARCH = 1;
    public const PUBLISHER_RATE   = 2;
    // File status
    public const PUBLISHER_STATUS_FILE_NOTSET   = -1;
    public const PUBLISHER_STATUS_FILE_ACTIVE   = 1;
    public const PUBLISHER_STATUS_FILE_INACTIVE = 2;
    // Image categories
    public const PUBLISHER_IMGCAT_ALL = 'all';
    // Constants for tables
    public const TABLE_CATEGORY = 0;
    public const TABLE_ARTICLE  = 1;
    // Constants for status
    public const STATUS_NONE      = 0;
    public const STATUS_OFFLINE   = 1;
    public const STATUS_SUBMITTED = 2;
    public const STATUS_APPROVED  = 3;
    public const STATUS_BROKEN    = 4;
    // Constants for permissions
    public const PERM_GLOBAL_NONE    = 0;
    public const PERM_GLOBAL_VIEW    = 1;
    public const PERM_GLOBAL_SUBMIT  = 2;
    public const PERM_GLOBAL_APPROVE = 3;
    // Constants for rating
    public const RATING_NONE     = 0;
    public const RATING_5STARS   = 1;
    public const RATING_10STARS  = 2;
    public const RATING_LIKES    = 3;
    public const RATING_10NUM    = 4;
    public const RATING_REACTION = 5;
    // Constants for tables
    public const TABLE_EVENTS             = 1;
    public const TABLE_CATEGORIES         = 2;
    public const TABLE_REGISTRATIONS      = 3;
    public const TABLE_QUESTIONS          = 4;
    public const TABLE_ANSWERS            = 5;
    public const TABLE_TEXTBLOCKS         = 6;
    public const TABLE_FIELDS             = 7;
    public const TABLE_REGISTRATIONS_HIST = 8;
    public const TABLE_ANSWERS_HIST       = 9;
    // Constants for status
    //    public const STATUS_NONE      = 0;
    //    public const STATUS_OFFLINE   = 1;
    //    public const STATUS_SUBMITTED = 2;
    public const STATUS_ONLINE    = 3;
    public const STATUS_VERIFIED  = 4;
    public const STATUS_APPROVED2 = 5;
    public const STATUS_LOCKED    = 6;
    public const STATUS_CANCELED  = 7;
    // Constants for permissions
    //    public const PERM_GLOBAL_NONE             = 0;
    //    public const PERM_GLOBAL_VIEW             = 1;
    //    public const PERM_GLOBAL_SUBMIT           = 2;
    //    public const PERM_GLOBAL_APPROVE          = 3;
    public const PERM_EVENTS_VIEW             = 4;
    public const PERM_EVENTS_SUBMIT           = 5;
    public const PERM_EVENTS_APPROVE          = 6;
    public const PERM_EVENTS_APPROVE_AUTO     = 7;
    public const PERM_REGISTRATIONS_AUTOVERIF = 8;
    public const PERM_REGISTRATIONS_SUBMIT    = 9;
    // Constants for question types
    public const FIELD_NONE      = 0;
    public const FIELD_LABEL     = 1;
    public const FIELD_TEXTBOX   = 2;
    public const FIELD_TEXTAREA  = 3;
    public const FIELD_RADIO     = 4;
    public const FIELD_RADIOYN   = 5;
    public const FIELD_SELECTBOX = 6;
    public const FIELD_COMBOBOX  = 7;
    public const FIELD_CHECKBOX  = 8;
    public const FIELD_DATE      = 9;
    public const FIELD_DATETIME  = 10;
    public const FIELD_NAME      = 11;
    public const FIELD_EMAIL     = 12;
    public const FIELD_COUNTRY   = 13;
}
