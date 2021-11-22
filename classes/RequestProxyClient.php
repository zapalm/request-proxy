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

    /** @var CurlHelper Помощник по работе с запросами через curl */
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
     * @param string $serverUrl Url проксирующего сервиса.
     * @param array  $config    Данные о запросах, которые должны быть сделаны через прокси.
     * @param string $token     Токен безопасности (вместо пароля).
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function __construct($serverUrl, array $config, $token) {
        $this->curlHelper   = new CurlHelper();
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
        $response = $this->curlHelper
            ->setUrl($this->serverUrl . '?token=' . $this->token)
            ->setReturn(true)
            ->setHeader(false)
            ->setPost(true)
            ->setConnectTimeOut(5)
            ->setTimeOut(0)
            ->setPostFields('content=' . urlencode(json_encode($this->requestsData)))
            ->execute()
        ;

        if (false === $response) {
            $this->addError('cURL вернул ошибку: #' . $this->getCurlErrorCode());

            return false;
        }

        $data = @json_decode($response); /** @var RequestProxyResponse|\stdClass $data */
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
     * Получить список ошибок.
     *
     * @return string[]
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Получить код последней ошибки cURL.
     *
     * @return int Код ошибки или 0, если ошибок не было.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function getCurlErrorCode() {
        return $this->curlHelper->getErrorCode();
    }
}