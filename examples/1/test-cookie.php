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
 * Запрашиваемая страница для тестирования, которая устанавливает cookie.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */

setcookie('TestCookie', 'test', time() + 3600); // Срок действия на 1 час.
echo 'OK' . PHP_EOL;
echo 'Cookie: ' . $_COOKIE['TestCookie'] . PHP_EOL;