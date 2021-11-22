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
 * Ответ проксирующего сервиса.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */
class RequestProxyResponse {

    /** @var bool Результат */
    public $result;

    /** @var string Ошибка */
    public $error;

    /** @var string Контент */
    public $content;

    /**
     * Конструктор.
     *
     * @param bool   $result  Результат.
     * @param string $error   Ошибка.
     * @param string $content Контент.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function __construct($result, $error, $content) {
        $this->result  = $result;
        $this->error   = $error;
        $this->content = $content;
    }
}