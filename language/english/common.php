<?php declare(strict_types=1);

/**
 * Module: Publisher
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
define('_CO_PUBLISHER_MESSAGE_FILE_ERROR', 'Error: Unable to store uploaded file for the following reasons:<br>%s');
define('_CO_PUBLISHER_MESSAGE_WRONG_MIMETYPE', 'Error: file type is not allowed. Please re-submit.');
define('_CO_PUBLISHER_ALLOWCOMMENTS', 'Can article be commented?');
define('_CO_PUBLISHER_AUTHOR_ALIAS', 'Author alias');
define('_CO_PUBLISHER_AUTHOR_ALIAS_DSC', 'Select the alias name of the poster, this will be used instead of “anonymous” and will set the owner ID of the article to 0');
define('_CO_PUBLISHER_AVAILABLE_PAGE_WRAP', 'Available pages to wrap');
define('_CO_PUBLISHER_AVAILABLE_PAGE_WRAP_DSC', 'Here are the pages available for wrapping in the body. Click on the page(s) you would like to wrap. Works with XOOPS editor only for the moment. Add manually if you are on another editor.');
define('_CO_PUBLISHER_DATESUB', 'Published');
define('_CO_PUBLISHER_DATESUB_DSC', 'Select the date of publication');
define('_CO_PUBLISHER_DATEEXPIRE', 'Expiration date');
define('_CO_PUBLISHER_DATEEXPIRE_DSC', 'Want you select a expiration date? Article will not shown anymore if expired');
define('_CO_PUBLISHER_ITEM_META_DESCRIPTION', 'Meta Description');
define('_CO_PUBLISHER_ITEM_META_DESCRIPTION_DSC', 'In order to help Search Engines, you can customize the meta description you would like to use for this article. if you leave this field empty when creating a category, it will automatically be populated with the Summary field of this article.');
define('_CO_PUBLISHER_ITEM_META_KEYWORDS', 'Meta Keywords');
define('_CO_PUBLISHER_ITEM_META_KEYWORDS_DSC', 'In order to help Search Engines, you can customize the keywords you would like to use for this article. If you leave this field empty when creating an article, it will automatically be populated with words from the Summary field of this article.');
define('_CO_PUBLISHER_ITEM_SHORT_URL', 'Short URL');
define('_CO_PUBLISHER_ITEM_SHORT_URL_DSC', 'When using the SEO features of this module, you can specify a Short URL for this article. This field is optional.');
//define('_CO_PUBLISHER_PERMISSIONS_ITEM', 'Permissions');
//define('_CO_PUBLISHER_PERMISSIONS_ITEM_DSC', 'Groups that will have permissions to see this item.');
define('_CO_PUBLISHER_SUBMITTED', 'Submitted');
define('_CO_PUBLISHER_PUBLISHED', 'Published');
define('_CO_PUBLISHER_OFFLINE', 'Offline');
define('_CO_PUBLISHER_REJECTED', 'Rejected');
define('_CO_PUBLISHER_STATUS', 'Status');
define('_CO_PUBLISHER_STATUS_DSC', 'Select the status of this article');
define('_CO_PUBLISHER_UID', 'Poster name');
define('_CO_PUBLISHER_UID_DSC', 'Select the name of the poster');
define('_CO_PUBLISHER_WEIGHT', 'Weight');
define('_CO_PUBLISHER_ITEM_UPLOAD_FILE', 'File upload');
define(
    '_CO_PUBLISHER_ITEM_UPLOAD_FILE_DSC',
    'Select a file from your computer to attach it to this article. You will be able to add more files once the article has been created. Simply edit the article and scroll at the bottom of the page to see the add file button.<br><br> for example, you could add a Word document or an Excel document. You can even upload a Flash file and it will be directly embedded into your article! '
);
//define('_CO_PUBLISHER_OPTIONS','Options');
define('_CO_PUBLISHER_DISPLAY_SUMMARY', 'Display summary on the item page ?');
define('_CO_PUBLISHER_DOHTML', 'Enable HTML tags');
define('_CO_PUBLISHER_DOIMAGE', 'Enable images');
define('_CO_PUBLISHER_DOLINEBREAK', 'Enable line break');
define('_CO_PUBLISHER_DOSMILEY', 'Enable smiley icons');
define('_CO_PUBLISHER_DOXCODE', 'Enable XOOPS codes');
define('_CO_PUBLISHER_EDIT', 'Edit article');
define('_CO_PUBLISHER_CLONE', 'Duplicate article');
define('_CO_PUBLISHER_ADD_FILE', 'Add a file');
define('_CO_PUBLISHER_DELETE', 'Delete article');
define('_CO_PUBLISHER_PDF', 'View this article in PDF format');
define('_CO_PUBLISHER_PRINT', 'Print article');
define('_CO_PUBLISHER_MAIL', 'Send article');
define('_CO_PUBLISHER_INTITEM', 'Have a look at this article at %s');
define('_CO_PUBLISHER_INTITEMFOUND', 'Here is an interesting article I have found at %s');
define('_CO_PUBLISHER_POSTEDBY', 'Published by %s on %s');
define('_CO_PUBLISHER_BODY', 'Body');
define('_CO_PUBLISHER_BODY_DSC', "Article's body");
define('_CO_PUBLISHER_CATEGORY', 'Category');
define('_CO_PUBLISHER_CATEGORY_DSC', "Article's category.");
define('_CO_PUBLISHER_IMAGE_ITEM', 'Article featured image');
define('_CO_PUBLISHER_IMAGE_ITEM_DSC', 'Image representing the article');
define('_CO_PUBLISHER_IMAGE_UPLOAD', 'Image upload');
//define('_CO_PUBLISHER_IMAGE_UPLOAD_ITEM_DSC','Select an image on your computer. <br>This image will be uploaded to the site <br>and set as the article image.');
define('_CO_PUBLISHER_SUBCATEGORIES_INFO', 'Subcategories within <em>%s</em> :');
define('_CO_PUBLISHER_SUMMARY', 'Block summary');
define('_CO_PUBLISHER_SUMMARY_DSC', 'This summary is used for blocks, index and category pages. It does not display inside article.');
define('_CO_PUBLISHER_TITLE', 'Title');
define('_CO_PUBLISHER_SUBTITLE', 'Sub title');
define('_CO_PUBLISHER_ERROR', 'Sorry, some error occurred!');
define('_CO_PUBLISHER_SORTBY', 'Sort by');
define('_CO_PUBLISHER_ADD', 'Add');
define('_CO_PUBLISHER_REMOVE', 'Remove');
define('_CO_PUBLISHER_PREVIEW', 'Preview');
define('_CO_PUBLISHER_CREATE', 'Create');
define('_CO_PUBLISHER_CLEAR', 'Clear');
define('_CO_PUBLISHER_CANCEL', 'Cancel');
define('_CO_PUBLISHER_IMAGE_ITEMS', 'Article images');
define('_CO_PUBLISHER_IMAGE_ITEMS_DSC', 'Please choose the images related to this article');
define('_CO_PUBLISHER_IMAGE_PREVIEW', 'Image preview');
define('_CO_PUBLISHER_NOTIFY', 'Notify on publish?');
define('_CO_PUBLISHER_FILEUPLOAD_ERROR', 'An error occurred while uploading the file.');
define('_CO_PUBLISHER_FILEUPLOAD_SUCCESS', 'The file was successfully uploaded.');
define('_CO_PUBLISHER_NEW_FEATURE', 'New feature!');
define('_CO_PUBLISHER_TAB_MAIN', 'Main');
define('_CO_PUBLISHER_TAB_IMAGES', 'Images');
define('_CO_PUBLISHER_TAB_OTHERS', 'Others');
define('_CO_PUBLISHER_TAB_META', 'Metadata');
define('_CO_PUBLISHER_TAB_PERMISSIONS', 'Permissions');
define('_CO_PUBLISHER_IMAGE_UPLOAD_NEW', 'Upload new image');
//define('_CO_PUBLISHER_IMAGE_UPLOADING','Uploading');
define('_CO_PUBLISHER_IMAGE_NICENAME', 'Enter image name');
define('_CO_PUBLISHER_IMAGE_CAT_NONE', 'No image category found');
define('_CO_PUBLISHER_IMAGE_CAT_NOPERM', 'You have no permissions to use this image category');
//30/04/2012
define('_CO_PUBLISHER_TAB_FILES', 'Files');
define('_CO_PUBLISHER_FILE', 'Files');
define('_CO_PUBLISHER_FILE_DESCRIPTION', 'Description');
define('_CO_PUBLISHER_FILE_DESCRIPTION_DSC', 'Description of the file to be uploaded.');
define('_CO_PUBLISHER_FILE_NAME_DSC', 'Name that will be used to identify the file.');
define('_CO_PUBLISHER_FILE_STATUS', 'File visible?');
define('_CO_PUBLISHER_FILE_STATUS_DSC', 'if you select no, the file will not be visible from the user side.');
define('_CO_PUBLISHER_FILE_TO_UPLOAD', 'File to upload:');
define('_CO_PUBLISHER_FILE_TYPE', 'File type');
define('_CO_PUBLISHER_FILE_UPLOAD_ANOTHER', 'Upload again');
define('_CO_PUBLISHER_FILENAME', 'File name');
define('_CO_PUBLISHER_FILES_LINKED', 'Files linked to this article');
//Added 30/05/2012
define('_CO_PUBLISHER_EDITFILE', 'Edit file');
define('_CO_PUBLISHER_DELETEFILE', 'Delete file');
//added 2017-05-16
define('_CO_PUBLISHER_BAD_TOKEN', 'Invalid token, please try again');

//2017-11-22

$moduleDirName      = \basename(\dirname(__DIR__, 2));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

define('CO_' . $moduleDirNameUpper . '_GDLIBSTATUS', 'GD library support: ');
define('CO_' . $moduleDirNameUpper . '_GDLIBVERSION', 'GD Library version: ');
define('CO_' . $moduleDirNameUpper . '_GDOFF', "<span style='font-weight: bold;'>Disabled</span> (No thumbnails available)");
define('CO_' . $moduleDirNameUpper . '_GDON', "<span style='font-weight: bold;'>Enabled</span> (Thumbsnails available)");
define('CO_' . $moduleDirNameUpper . '_IMAGEINFO', 'Server status');
define('CO_' . $moduleDirNameUpper . '_MAXPOSTSIZE', 'Max post size permitted (post_max_size directive in php.ini): ');
define('CO_' . $moduleDirNameUpper . '_MAXUPLOADSIZE', 'Max upload size permitted (upload_max_filesize directive in php.ini): ');
define('CO_' . $moduleDirNameUpper . '_MEMORYLIMIT', 'Memory limit (memory_limit directive in php.ini): ');
define('CO_' . $moduleDirNameUpper . '_METAVERSION', "<span style='font-weight: bold;'>Downloads meta version:</span> ");
define('CO_' . $moduleDirNameUpper . '_OFF', "<span style='font-weight: bold;'>OFF</span>");
define('CO_' . $moduleDirNameUpper . '_ON', "<span style='font-weight: bold;'>ON</span>");
define('CO_' . $moduleDirNameUpper . '_SERVERPATH', 'Server path to XOOPS root: ');
define('CO_' . $moduleDirNameUpper . '_SERVERUPLOADSTATUS', 'Server uploads status: ');
define('CO_' . $moduleDirNameUpper . '_SPHPINI', "<span style='font-weight: bold;'>Information taken from PHP ini file:</span>");
define('CO_' . $moduleDirNameUpper . '_UPLOADPATHDSC', 'Note. Upload path *MUST* contain the full server path of your upload folder.');

define('CO_' . $moduleDirNameUpper . '_PRINT', "<span style='font-weight: bold;'>Print</span>");
define('CO_' . $moduleDirNameUpper . '_PDF', "<span style='font-weight: bold;'>Create PDF</span>");

define('CO_' . $moduleDirNameUpper . '_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('CO_' . $moduleDirNameUpper . '_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('CO_' . $moduleDirNameUpper . '_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('CO_' . $moduleDirNameUpper . '_ERROR_COLUMN', 'Could not create column in database : %s');
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('CO_' . $moduleDirNameUpper . '_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');

define('CO_' . $moduleDirNameUpper . '_FOLDERS_DELETED_OK', 'Upload Folders have been deleted');

// Error Msgs
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_DEL_PATH', 'Could not delete %s directory');
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_REMOVE', 'Could not delete %s');
define('CO_' . $moduleDirNameUpper . '_ERROR_NO_PLUGIN', 'Could not load plugin');

//Help
define('CO_' . $moduleDirNameUpper . '_DIRNAME', basename(dirname(__DIR__, 2)));
define('CO_' . $moduleDirNameUpper . '_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('CO_' . $moduleDirNameUpper . '_BACK_2_ADMIN', 'Back to Administration of ');
define('CO_' . $moduleDirNameUpper . '_OVERVIEW', 'Overview');

//define('CO_' . $moduleDirNameUpper . '_HELP_DIR', __DIR__);

//help multipage
define('CO_' . $moduleDirNameUpper . '_DISCLAIMER', 'Disclaimer');
define('CO_' . $moduleDirNameUpper . '_LICENSE', 'License');
define('CO_' . $moduleDirNameUpper . '_SUPPORT', 'Support');

//Sample Data
\define('CO_' . $moduleDirNameUpper . '_' . 'LOAD_SAMPLEDATA', 'Import Sample Data (will delete ALL current data)');
\define('CO_' . $moduleDirNameUpper . '_' . 'LOAD_SAMPLEDATA_CONFIRM', 'Are you sure to Import Sample Data? (It will delete ALL current data)');
\define('CO_' . $moduleDirNameUpper . '_' . 'LOAD_SAMPLEDATA_SUCCESS', 'Sample Date imported  successfully');
\define('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA', 'Export Tables to YAML');
\define('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA_SUCCESS', 'Export Tables to YAML successfully');
\define('CO_' . $moduleDirNameUpper . '_' . 'CLEAR_SAMPLEDATA', 'Clear Sample Data');
\define('CO_' . $moduleDirNameUpper . '_' . 'CLEAR_SAMPLEDATA_OK', 'The Sample Data has been cleared');
\define('CO_' . $moduleDirNameUpper . '_' . 'CLEAR_SAMPLEDATA_CONFIRM', 'Are you sure to Clear Sample Data? (It will delete ALL current data)');
\define('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA', 'Export DB Schema to YAML');
\define('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_SUCCESS', 'Export DB Schema to YAML was a success');
\define('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_ERROR', 'ERROR: Export of DB Schema to YAML failed');
\define('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON', 'Show Sample Button?');
\define('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC', 'If yes, the "Add Sample Data" button will be visible to the Admin. It is Yes as a default for first installation.');
\define('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS', 'Hide the Import buttons)');
\define('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS', 'Show the Import buttons)');

\define('CO_' . $moduleDirNameUpper . '_' . 'CONFIRM', 'Confirm');

//letter choice
define('CO_' . $moduleDirNameUpper . '_' . 'BROWSETOTOPIC', "<span style='font-weight: bold;'>Browse items alphabetically</span>");
define('CO_' . $moduleDirNameUpper . '_' . 'OTHER', 'Other');
define('CO_' . $moduleDirNameUpper . '_' . 'ALL', 'All');

// block defines
define('CO_' . $moduleDirNameUpper . '_' . 'ACCESSRIGHTS', 'Access Rights');
define('CO_' . $moduleDirNameUpper . '_' . 'ACTION', 'Action');
define('CO_' . $moduleDirNameUpper . '_' . 'ACTIVERIGHTS', 'Active Rights');
define('CO_' . $moduleDirNameUpper . '_' . 'BADMIN', 'Block Administration');
define('CO_' . $moduleDirNameUpper . '_' . 'BLKDESC', 'Description');
define('CO_' . $moduleDirNameUpper . '_' . 'CBCENTER', 'Center Middle');
define('CO_' . $moduleDirNameUpper . '_' . 'CBLEFT', 'Center Left');
define('CO_' . $moduleDirNameUpper . '_' . 'CBRIGHT', 'Center Right');
define('CO_' . $moduleDirNameUpper . '_' . 'SBLEFT', 'Left');
define('CO_' . $moduleDirNameUpper . '_' . 'SBRIGHT', 'Right');
define('CO_' . $moduleDirNameUpper . '_' . 'SIDE', 'Alignment');
define('CO_' . $moduleDirNameUpper . '_' . 'TITLE', 'Title');
define('CO_' . $moduleDirNameUpper . '_' . 'VISIBLE', 'Visible');
define('CO_' . $moduleDirNameUpper . '_' . 'VISIBLEIN', 'Visible In');
define('CO_' . $moduleDirNameUpper . '_' . 'WEIGHT', 'Weight');

define('CO_' . $moduleDirNameUpper . '_' . 'PERMISSIONS', 'Permissions');
define('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS', 'Blocks Admin');
define('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS_DESC', 'Blocks/Group Admin');

define('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS_MANAGMENT', 'Manage');
define('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS_ADDBLOCK', 'Add a new block');
define('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS_EDITBLOCK', 'Edit a block');
define('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS_CLONEBLOCK', 'Clone a block');

//myblocksadmin
define('CO_' . $moduleDirNameUpper . '_' . 'AGDS', 'Admin Groups');
define('CO_' . $moduleDirNameUpper . '_' . 'BCACHETIME', 'Cache Time');
define('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS_ADMIN', 'Blocks Admin');
define('CO_' . $moduleDirNameUpper . '_' . 'UPDATE_SUCCESS', 'Update successful');

//Template Admin
define('CO_' . $moduleDirNameUpper . '_' . 'TPLSETS', 'Template Management');
define('CO_' . $moduleDirNameUpper . '_' . 'GENERATE', 'Generate');
define('CO_' . $moduleDirNameUpper . '_' . 'FILENAME', 'File Name');

//Menu
define('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE', 'Migrate');
define('CO_' . $moduleDirNameUpper . '_' . 'FOLDER_YES', 'Folder "%s" exist');
define('CO_' . $moduleDirNameUpper . '_' . 'FOLDER_NO', 'Folder "%s" does not exist. Create the specified folder with CHMOD 777.');
define('CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS', 'Show Development Tools Button?');
define('CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS_DESC', 'If yes, the "Migrate" Tab and other Development tools will be visible to the Admin.');
define('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_FEEDBACK', 'Feedback');
define('CO_' . $moduleDirNameUpper . '_' . 'MIGRATE_OK', 'Database migrated to current schema.');
define('CO_' . $moduleDirNameUpper . '_' . 'MIGRATE_WARNING', 'Warning! This is intended for developers only. Confirm write schema file from current database.');
define('CO_' . $moduleDirNameUpper . '_' . 'MIGRATE_SCHEMA_OK', 'Current schema file written');

//Latest Version Check
define('CO_' . $moduleDirNameUpper . '_' . 'NEW_VERSION', 'New Version: ');

//Module Stats
\define('CO_' . $moduleDirNameUpper . '_' . 'STATS_SUMMARY', 'Module Statistics');
\define('CO_' . $moduleDirNameUpper . '_' . 'TOTAL_CATEGORIES', 'Categories:');
\define('CO_' . $moduleDirNameUpper . '_' . 'TOTAL_ITEMS', 'Items');
\define('CO_' . $moduleDirNameUpper . '_' . 'TOTAL_OFFLINE', 'Offline');
\define('CO_' . $moduleDirNameUpper . '_' . 'TOTAL_PUBLISHED', 'Published');
\define('CO_' . $moduleDirNameUpper . '_' . 'TOTAL_REJECTED', 'Rejected');
\define('CO_' . $moduleDirNameUpper . '_' . 'TOTAL_SUBMITTED', 'Submitted');

//Preferences
define('CO_' . $moduleDirNameUpper . '_' . 'TRUNCATE_LENGTH', 'Number of Characters to truncate to the long text field');
define('CO_' . $moduleDirNameUpper . '_' . 'TRUNCATE_LENGTH_DESC', 'Set the maximum number of characters to truncate the long text fields');

\define('CO_' . $moduleDirNameUpper . '_' . 'DELETE_BLOCK_CONFIRM', 'Are you sure to delete this Block?');

//Cloning
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE', 'Clone');
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE_DSC', 'Cloning a module has never been this easy! Just type in the name you want for it and hit submit button!');
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE_TITLE', 'Clone %s');
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE_NAME', 'Choose a name for the new module');
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE_NAME_DSC', 'Do not use special characters! <br>Do not choose an existing module dirname or database table name!');
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE_INVALIDNAME', 'ERROR: Invalid module name, please try another one!');
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE_EXISTS', 'ERROR: Module name already taken, please try another one!');
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE_CONGRAT', 'Congratulations! %s was sucessfully created!<br>You may want to make changes in language files.');
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE_IMAGEFAIL', 'Attention, we failed creating the new module logo. Please consider modifying assets/images/logo_module.png manually!');
define('CO_' . $moduleDirNameUpper . '_' . 'CLONE_FAIL', "Sorry, we failed in creating the new clone. Maybe you need to temporally set write permissions (CHMOD 777) to 'modules' folder and try again.");

//JSON-LD generation of www.schema.org
\define('CO_' . $moduleDirNameUpper . '_' . 'GENERATE_JSONLD', 'Generate Schema Markup through JSON LD');
\define('CO_' . $moduleDirNameUpper . '_' . 'GENERATE_JSONLD_DESC', 'Mark up your module with structured data to help search engines better understand the content of your web page');

//Repository not found
define('CO_' . $moduleDirNameUpper . '_' . 'REPO_NOT_FOUND', 'Repository Not Found: ');
//Release not found
define('CO_' . $moduleDirNameUpper . '_' . 'NO_REL_FOUND', 'Released Version Not Found: ');
