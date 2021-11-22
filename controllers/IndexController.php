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

namespace zapalm\requestProxy\controllers;

use zapalm\requestProxy\classes\RequestProxyServer;

/**
 * Контроллер прокси-сервиса.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */
class IndexController {

    /** @var string Токен безопасности (вместо пароля) */
    private $token;

    /**
     * Конструктор.
     *
     * @param string $token Токен безопасности (вместо пароля).
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Обработка запроса клиента.
     *
     * @return string
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function actionIndex() {
        if ($this->token !== @$_GET['token']) {
            return $this->response(false, 'Неверный токен.');
        }

        $request = @$_POST['content'];
        if (null === $request) {
            return $this->response(false, 'Данные не переданы.');
        }

        $config = @json_decode($request, true);
        if (null === $config) {
            return $this->response(false, 'Неверный формат данных.');
        }

        $server = new RequestProxyServer($config);
        $server->execute();
        $response = $server->getResponse();

        return $this->response(true, $response);
    }

    /**
     * Ответить.
     *
     * @param bool         $result  Результат.
     * @param array|string $content Содержание результата.
     *
     * @return string
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    private function response($result, $content) {
        return json_encode([
            'result'  => $result,
            'content' => $content,
        ]);
    }
}