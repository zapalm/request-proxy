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

    /** @var RequestProxyConfig[]|array Массив с данными о запросах. */
    private $config;

    /** @var bool Разрешить импортирование параметров CurlHelper (обычно, не следует это делать - всё зависит от ситуации и кастомизации CurlHelper). */
    private $allowImportParams;

    /** @var bool Разрешить импортирование опций настройки CURL о промежуточном прокси. */
    private $allowIntermediateProxy;

    /** @var RequestProxyResponse[] Ответ прокси-клиенту с данными об ответах на запросы. */
    private $response = [];

    /**
     * Конструктор.
     *
     * @param RequestProxyConfig[]|array $config                 Массив с данными о запросах.
     * @param bool                       $allowImportParams      Разрешить импортирование параметров CurlHelper (обычно, не следует это делать - всё зависит от ситуации и кастомизации CurlHelper).
     * @param bool                       $allowIntermediateProxy Разрешить импортирование опций настройки CURL о промежуточном прокси.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function __construct(array $config, $allowImportParams = false, $allowIntermediateProxy = false) {
        $this->config                 = $config;
        $this->allowImportParams      = $allowImportParams;
        $this->allowIntermediateProxy = $allowIntermediateProxy;
    }

    /**
     * Выполнить.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function execute() {
        $cookies = null;

        foreach ($this->config as $requestConfig) {
            // -- Подготавливаем опции для настройки CurlHelper
            $requestConfig = (object)$requestConfig; /** @var RequestProxyConfig|object $requestConfig */

            unset($requestConfig->options[CURLOPT_CAINFO]);

            if (false === $this->allowIntermediateProxy) {
                unset($requestConfig->options[CURLOPT_PROXY]);
                unset($requestConfig->options[CURLOPT_PROXYUSERNAME]);
                unset($requestConfig->options[CURLOPT_PROXYPASSWORD]);
                unset($requestConfig->options[CURLOPT_PROXYTYPE]);
            }
            // -- -- --

            // -- Настраиваем CurlHelper
            $curlHelper = new CurlHelper();
            $curlHelper->importOptions($requestConfig->options);
            if ($this->allowImportParams && [] !== $requestConfig->params) {
                $curlHelper->importParams($requestConfig->params);
            }

            if (null !== $cookies) {
                $curlHelper->setCookie($cookies);
            }
            // -- -- --

            $response = $curlHelper->execute();
            if (false === $response) {
                $this->response[] = new RequestProxyResponse(false, 'CURL вернул ошибку: #' . $curlHelper->getErrorCode(), null);

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