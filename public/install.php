<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);

/**
 * Change variables
 */
$basePath   = realpath(__DIR__ . '/../');
$iniFile    = parse_ini_file(realpath($basePath . '/application/configs/application.ini'));
$pdoDsn     = 'mysql:host=localhost;dbname=test';
$pdoUser    = 'root';
$pdoPass    = '';
$loginName  = 'Administrator';
$loginUser  = 'admin';
$loginPass  = 'admin';
$pdoOptions = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
);

$sqlFile    = realpath($basePath . '/docs/db.sql');
$delimiter  = ';';

/**
 * No need for modification next lines
 */
try{
    $db = new PDO($pdoDsn, $pdoUser, $pdoPass, $pdoOptions);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /**
     * Install what is in $file
     *
     * @see http://stackoverflow.com/questions/1883079/best-practice-import-mysql-file-in-php-split-queries#answers-header
     */
    if (is_file($sqlFile) === true) {
        if (($file = fopen($sqlFile, 'r')) !== false) {
            $query = array();

            while (feof($file) === false) {
                $line = trim(fgets($file));

                if (empty($line) === false && preg_match('/^[^--]/', $line) === 1 && preg_match('/^[^\/.+?\/]/', $line) === 1) {
                    $query[] = $line;
                }

                if (preg_match('/' . preg_quote($delimiter, '/') . '\s*$/iS', end($query)) === 1) {
                    $dbQuery = trim(implode('', $query));

                    $db->exec($dbQuery);

                    $query = array();
                }
            }

            fclose($file);
        }
    }

    /**
     * Create admin user
     */
    $dbQuery = 'INSERT INTO `users` (
                    `users_id`,
                    `name`,
                    `num_empl`,
                    `pwd`,
                    `email`,
                    `role`,
                    `access`,
                    `change_pwd`,
                    `token_pwd`,
                    `in_charge`,
                    `created_by`,
                    `created_datetime`,
                    `created_by_remote_addr`,
                    `block_access`,
                    `block_by`,
                    `block_datetime`,
                    `block_by_remote_addr`,
                    `updated_by`,
                    `updated_datetime`,
                    `updated_by_remote_addr`,
                    `deleted_account`,
                    `deleted_by`,
                    `deleted_datetime`,
                    `deleted_by_remote_addr`
                ) VALUES (
                    null,
                    "' . ucwords(mb_strtolower($loginName)) . '",
                    "' . mb_strtoupper($loginUser) . '",
                    "' . crypt($loginPass, $iniFile['additionalParams.salt']) . '",
                    null,
                    "admin",
                    null,
                    0,
                    null,
                    null,
                    1,
                    "' . date('Y-m-d H:i:s') . '",
                    "' . $_SERVER['REMOTE_ADDR'] . '",
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                )';
    $db->exec($dbQuery);
} catch(PDOException $err) {
    exit($err->getMessage());
}

unlink($basePath . '/public/install.php');

/**
 * Redirect to login page
 */
header('Location: /');