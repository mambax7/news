<?php declare(strict_types=1);

/**
 * uninstall.php - cleanup on module uninstall
 *
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2.0 or later}
 * @link            https://xoops.org XOOPS
 */

use XoopsModules\News\{
    Helper,
    Utility
};

/**
 * Prepares system prior to attempting to uninstall module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to uninstall, false if not
 */
function xoops_module_pre_uninstall_news(\XoopsModule $module): bool
{
    // Do some synchronization
    return true;
}

/**
 * Performs tasks required during uninstallation of the module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if uninstallation successful, false if not
 */
function xoops_module_uninstall_news(\XoopsModule $module): bool
{
    //    return true;

    $moduleDirName      = \basename(\dirname(__DIR__));
    $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
    /** @var Helper $helper */
    $helper = Helper::getInstance();

    /** @var Utility $utility */
    $utility = new Utility();

    $success = true;
    $helper->loadLanguage('admin');

    // Rename uploads folder to BAK and add date to name
    $uploadDirectory = $GLOBALS['xoops']->path("uploads/$moduleDirName");
    $dirInfo = new \SplFileInfo($uploadDirectory);
    if ($dirInfo->isDir()) {
        // The directory exists so rename it
        $date = date('Y-m-d');
        if (!rename($uploadDirectory, $uploadDirectory . "_bak_$date")) {
            $module->setErrors(sprintf(constant('CO_' . $moduleDirNameUpper . '_' . 'ERROR_BAD_DEL_PATH'), $uploadDirectory));
            $success = false;
        }
    }
    unset($dirInfo);
    /*
    //------------ START ----------------
    //------------------------------------------------------------------
    // Remove xsitemap.xml from XOOPS root folder if it exists
    //------------------------------------------------------------------
    $xmlfile = $GLOBALS['xoops']->path('xsitemap.xml');
    if (\is_file($xmlfile)) {
        if (false === ($delOk = unlink($xmlfile))) {
            $module->setErrors(sprintf(_AM_XXXXX_ERROR_BAD_REMOVE, $xmlfile));
        }
    }
//    return $success && $delOk; // use this if you're using this routine
*/

    return $success;
    //------------ END  ----------------
}
