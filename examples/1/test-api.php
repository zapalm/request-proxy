<?php
/**
 * HTTP request proxy library.
 *
 * @author    Maksim T. <zapalm@yandex.com>
 * @copyright 2018 Maksim T.
 * @license   MIT
 * @link      https://github.com/zapalm/request-proxy GitHub
 * @link      https://zapalm.ru Author's Homepage
 */

/**
 * Точка входа.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */

$debug = false;
if ($debug) {
    error_reporting(E_ALL & ~E_DEPRECATED);
    ini_set('display_errors', 'on');
}

require_once __DIR__ . '/../../vendor/autoload.php';

use zapalm\requestProxy\controllers\IndexController;

$controller = new IndexController('CwbidqMDcjJSPNFGWz1y');
$response = $controller->actionIndex();

echo $response;
