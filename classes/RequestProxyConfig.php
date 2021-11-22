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

namespace zapalm\requestProxy\classes;

/**
 * Конфигурация запроса для проксирующего сервиса.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */
class RequestProxyConfig {

    /** @var bool Данные о запросе для получения cookie */
    public $cookie;

    /** @var string[] Опции настройки cUrl */
    public $options;

    /** @var string[] Параметры запроса */
    public $params;

    /**
     * Конструктор.
     *
     * @param bool     $cookie  Данные о запросе для получения cookie.
     * @param string[] $options Опции настройки cUrl.
     * @param string[] $params  Параметры запроса.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function __construct($cookie, array $options, array $params) {
        $this->cookie  = $cookie;
        $this->options = $options;
        $this->params  = $params;
    }
}