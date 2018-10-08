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
 * Запрашиваемая страница для тестирования, которая устнавливает cookie.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */

setcookie('TestCookie', 'test', time() + 3600); // Срок действия на 1 час.
echo 'OK' . PHP_EOL;
echo 'Cookie: ' . $_COOKIE['TestCookie'] . PHP_EOL;