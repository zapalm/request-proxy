<?php
/**
 * HTTP request proxy library.
 *
 * @author    Maksim T. <zapalm@yandex.com>
 * @copyright 2018 Maksim T.
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/zapalm/requestProxy GitHub
 * @link      http://zapalm.ru/ Author's Homepage
 */

/**
 * Контроллер.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once __DIR__ . '/vendor/autoload.php';

$controller = new zapalm\requestProxy\controllers\IndexController('1Ruk2BnIoAinRPCpBcPR');
$response = $controller->actionIndex();

echo $response;