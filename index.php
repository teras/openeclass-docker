<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

require_once '../vendor/autoload.php';
require_once '../include/main_lib.php';
require_once '../include/mailconfig.php';
require_once '../modules/db/database.php';
require_once '../upgrade/functions.php';
require_once 'functions.php';

$tool_content = '';

$siteName = '';
$InstitutionUrl = '';
$Institution = '';

if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('Europe/Athens');
}

// get installation language. Greek is the default language.
if (isset($_REQUEST['lang'])) {
    $lang = $_POST['lang'] = $_SESSION['lang'] = $_REQUEST['lang'];
} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
} else {
    $lang = 'el';
}
if (!isset($language_codes[$lang])) {
    $lang = 'el';
}

// include_messages
require_once "../lang/$lang/common.inc.php";
$extra_messages = "../config/{$language_codes[$lang]}.inc.php";
if (file_exists($extra_messages)) {
    include $extra_messages;
} else {
    $extra_messages = false;
}
require_once "../lang/$lang/messages.inc.php";
if ($extra_messages) {
    include $extra_messages;
}

$phpSysInfoURL = '../admin/sysinfo/';
$dbMyAdmin = $emailForm = '';
$urlForm = ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']) ? 'https://' : 'http://') .
        $_SERVER['SERVER_NAME'] .
        str_replace('/install/index.php', '/', $_SERVER['SCRIPT_NAME']);
$nameForm = $langDefaultAdminName;

// admin credentials
$loginForm = 'admin';
$passForm = 'openeclass';

$campusForm = 'Open eClass';
$helpdeskForm = '+30 2xx xxxx xxx';
$institutionForm = $langDefaultInstitutionName;
$institutionUrlForm = 'http://www.gunet.gr/';
$dbPassForm = $helpdeskmail = $faxForm = $postaddressForm = '';
$eclass_stud_reg = 2;
$eclass_prof_reg = 1;
$active_ui_languages = 'el en';

// db credentials
$dbNameForm = 'eclass-docker';
$dbHostForm = 'localhost';
$dbUsernameForm = 'root';
$dbPassForm = 'admin';

$GLOBALS['mysqlMainDb'] = $dbNameForm;
$GLOBALS['mysqlServer'] = $dbHostForm;
$GLOBALS['mysqlUser'] = $dbUsernameForm;
$GLOBALS['mysqlPassword'] = $dbPassForm;

Debug::setLevel(Debug::ALWAYS);
Database::core();

// create main database
require 'install_db.php';

    // create config.php
    $stringConfig = '<?php
/* ========================================================
 * Open eClass 3.x configuration file
 * Created by install on ' . date('Y-m-d H:i') . '
 * ======================================================== */

$mysqlServer = ' . quote($dbHostForm) . ';
$mysqlUser = ' . quote($dbUsernameForm) . ';
$mysqlPassword = ' . quote($dbPassForm) . ';
$mysqlMainDb = ' . quote($mysqlMainDb) . ';
';
    $fd = @fopen("../config/config.php", "w");

// write to file
fwrite($fd, $stringConfig);

$installDir = dirname(dirname(__FILE__));
// install certificate templates
installCertTemplates($installDir);
// install badge icons
installBadgeIcons($installDir);
chdir(dirname(__FILE__));

$_SESSION['langswitch'] = $lang;

// create config, courses directories etc.
mkdir_try('config');
touch_try('config/index.php');
mkdir_try('courses');
touch_try('courses/index.php');
mkdir_try('courses/temp');
touch_try('courses/temp/index.php');
mkdir_try('courses/temp/pdf');
mkdir_try('courses/userimg');
touch_try('courses/userimg/index.php');
mkdir_try('courses/commondocs');
touch_try('courses/commondocs/index.php');
mkdir_try('video');
touch_try('video/index.php');
mkdir_try('courses/user_progress_data');
mkdir_try('courses/user_progress_data/cert_templates');
touch_try('courses/user_progress_data/cert_templates/index.php');
mkdir_try('courses/user_progress_data/badge_templates');
touch_try('courses/user_progress_data/badge_templates/index.php');
mkdir_try('courses/eportfolio');
touch_try('courses/eportfolio/index.php');
mkdir_try('courses/eportfolio/userbios');
touch_try('courses/eportfolio/userbios/index.php');
mkdir_try('courses/eportfolio/work_submissions');
touch_try('courses/eportfolio/work_submissions/index.php');
mkdir_try('courses/eportfolio/mydocs');
touch_try('courses/eportfolio/mydocs/index.php');
