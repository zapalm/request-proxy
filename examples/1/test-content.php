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
 * Запрашиваемая страница для тестирования, для которой требуется, чтобы были установлены cookie.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */

if (!isset($_COOKIE['TestCookie'])) {
    echo 'ERR: cookie is not set.' . PHP_EOL;
    exit;
}

echo 'OK' . PHP_EOL;
echo 'POST: ' . print_r($_POST, true) . PHP_EOL;
echo 'Cookie: ' . $_COOKIE['TestCookie'] . PHP_EOL;
