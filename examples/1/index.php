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
 * Контроллер.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once __DIR__ . '/../../vendor/autoload.php';

use zapalm\requestProxy\controllers\IndexController;

$controller = new IndexController('CwbidqMDcjJSPNFGWz1y');
$response = $controller->actionIndex();

echo $response;
