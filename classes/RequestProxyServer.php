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
 * Сервер проксирующего сервиса.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */
class RequestProxyServer {

    /** @var RequestProxyConfig[] Данные о запросах */
    private $config;

    /** @var RequestProxyResponse[] Ответ клиенту */
    private $response = [];

    /**
     * Конструктор.
     *
     * @param RequestProxyConfig[] $config Данные о запросах.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Выполнить.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function execute() {
        $cookies = null;

        foreach ($this->config as $requestConfig) {
            $curlHelper = new CurlHelper();

            $requestConfig = (object)$requestConfig;
            unset($requestConfig->options[CURLOPT_CAINFO]);
            unset($requestConfig->options[CURLOPT_PROXY]);
            unset($requestConfig->options[CURLOPT_PROXYUSERNAME]);
            unset($requestConfig->options[CURLOPT_PROXYPASSWORD]);
            unset($requestConfig->options[CURLOPT_PROXYTYPE]);

            $curlHelper->importOptions($requestConfig->options);
            $curlHelper->importParams($requestConfig->params);
            if (null !== $cookies) {
                $curlHelper->setCookie($cookies);
            }

            $response = $curlHelper->execute();
            if (false === $response) {
                $this->response[] = new RequestProxyResponse(false, 'cURL вернул ошибку: #' . $curlHelper->getErrorCode(), null);

                break;
            }

            if ($requestConfig->cookie) {
                $cookies = $curlHelper::parseCookie($response);
                if (null === $cookies) {
                    $this->response[] = new RequestProxyResponse(false, 'Не удалось разобрать cookie.', $response);

                    break;
                }

                continue;
            }

            $this->response[] = new RequestProxyResponse(true, null, $response);
        }
    }

    /**
     * Получить ответ, который нужно вернуть клиенту.
     *
     * @return RequestProxyResponse[]
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function getResponse() {
        return $this->response;
    }
}