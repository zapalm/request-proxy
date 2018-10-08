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
    private $host;

    /** @var RequestProxyResponse[] Контент */
    private $content;

    /** @var string[] Ошибки */
    private $errors = [];

    /**
     * Конструктор.
     *
     * @param string $host   Хост проксирующего сервиса.
     * @param array  $config Данные о запросах, которые должны быть сделаны через прокси.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function __construct($host, array $config) {
        $this->curlHelper = new CurlHelper();
        $this->host = $host;
        $this->requestsData = $config;
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
            ->setUrl('http://' . $this->host . '/request-proxy/index.php?token=1Ruk2BnIoAinRPCpBcPR')
            ->setReturn(true)
            ->setHeader(false)
            ->setPost(true)
            ->setConnectTimeOut(5)
            ->setTimeOut(0)
            ->setPostFields('content=' . json_encode($this->requestsData))
            ->execute()
        ;

        if (false === $response) {
            $this->addError('cURL вернул ошибку: #' . $this->getCurlErrorCode());

            return false;
        }

        $data = @json_decode($response); /** @var RequestProxyResponse|\stdClass $data */
        if (null === $data) {
            $this->addError('Неудалось декодировать ответ: ' . PHP_EOL . $response);

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