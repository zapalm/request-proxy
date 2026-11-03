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

use zapalm\curlHelper\CurlHelper;

/**
 * Клиент проксирующего сервиса.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */
class RequestProxyClient {

    /** @var CurlHelper Экземпляр CurlHelper для отправки HTTP-запросов */
    private $curlHelper;

    /** @var RequestProxyConfig[] Данные о запросах, которые должны быть сделаны через прокси */
    private $requestsData;

    /** @var string Хост проксирующего сервиса */
    private $serverUrl;

    /** @var RequestProxyResponse[] Контент */
    private $content;

    /** @var string Токен безопасности (вместо пароля) */
    private $token;

    /** @var string[] Ошибки */
    private $errors = [];

    /**
     * Конструктор.
     *
     * @param string          $serverUrl  Url проксирующего сервиса.
     * @param array           $config     Данные о запросах, которые должны быть сделаны через прокси.
     * @param string          $token      Токен безопасности (вместо пароля).
     * @param CurlHelper|null $curlHelper Экземпляр CurlHelper (для индивидуальной настройки и переиспользования).
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function __construct($serverUrl, array $config, $token, CurlHelper $curlHelper = null) {
        $this->curlHelper   = $curlHelper;
        $this->serverUrl    = $serverUrl;
        $this->requestsData = $config;
        $this->token        = $token;
    }

    /**
     * Выполнить.
     *
     * @return bool
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function execute() {
        if (null === $this->curlHelper) {
            $this->curlHelper = (new CurlHelper())
                ->setConnectTimeOut(5)
                ->setTimeOut(0) // Не накладываем ограничение, т.к. некоторые ресурсы могут отвечать долго (делегируем веб-серверу сервиса - он сам отбросит запрос по своему таймауту)
                ->setReturn(true)
                ->setHeader(false)
                ->setPost(true)
                ->setEncoding('')
            ;
        }

        $response = $this->curlHelper
            ->setUrl($this->serverUrl)
            ->setPostFields(http_build_query([
                'content' => json_encode($this->requestsData),
                'token'   => $this->token,
            ]))
            ->execute()
        ;

        if (false === $response) {
            $this->addError('CURL вернул ошибку: #' . $this->getCurlErrorCode());

            return false;
        }

        $data = json_decode($response); /** @var RequestProxyResponse|\stdClass $data */
        if (null === $data) {
            $this->addError('Не удалось декодировать ответ: ' . PHP_EOL . $response);

            return false;
        }

        if (false === $data->result) {
            $this->addError('Сервис вернул ошибку: ' . $data->content);

            return false;
        }

        $this->content = $data->content;

        return true;
    }

    /**
     * Получить контент.
     *
     * @return RequestProxyResponse[]
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Установить ошибку.
     *
     * @param string $error
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function addError($error) {
        $this->errors[] = basename(static::class) . ': ' . $error;
    }

    /**
     * Получить сообщение об ошибке.
     *
     * @return string
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function getErrorMessage() {
        return implode(' ', $this->errors);
    }

    /**
     * Получить список ошибок.
     *
     * @return string[]
     *
     * @deprecated Т.к. метод не удобен, т.к. ошибка всегда одна. См. замену: {@see getErrorMessage()}.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Получить код последней ошибки CURL.
     *
     * @return int Код ошибки или 0, если ошибок не было.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function getCurlErrorCode() {
        return $this->curlHelper->getErrorCode();
    }
}