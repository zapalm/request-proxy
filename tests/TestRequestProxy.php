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

require_once __DIR__ . '/../vendor/autoload.php';

use zapalm\curlHelper\CurlHelper;
use zapalm\requestProxy\classes\RequestProxyClient;
use zapalm\requestProxy\classes\RequestProxyConfig;

/**
 * Тест работы проксирующего сервиса.
 *
 * Запуск разрешён только на локальном ПК - см. файл ".htaccess" в корне.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */
class TestRequestProxy extends PHPUnit_Framework_TestCase
{
    /**
     * Тест обработки запроса проксирующим сервисом на примере 1 (/examples/1).
     */
    public function testRequestByExample1()
    {
        // Входные данные для теста (отредактировать при необходимости запуска теста)
        $host  = 'localhost';
        $path  = 'http://' . $host . '/request-proxy/examples/1';
        $token = 'CwbidqMDcjJSPNFGWz1y';

        $requestProxyConfig = [];

        // -- Формируем запрос для получения cookie для запроса через прокси
        $curlHelper = (new CurlHelper())
            ->setReturn(true)
            ->setHeader(true)
            ->setAutoReferer(true)
            ->setHttpHeader([
                'Accept: */*',
                'Cache-Control: no-cache',
                'Connection: keep-alive',
            ])
            ->setUrl($path . '/test-cookie.php')
        ;

        $requestProxyConfig[] = new RequestProxyConfig(true, $curlHelper->exportOptions(), $curlHelper->exportParams());
        // -- -- --

        // -- Формируем запрос на конечный ресурс, который должен подать прокси
        $contentUrl = $path . '/test-content.php';

        $curlHelper = (new CurlHelper())
            ->setUrl($contentUrl)
            ->setReturn(true)
            ->setHeader(false)
            ->setPost(true)
            ->setReferer('http://' . $host)
            ->setHttpHeader([
                'Accept: */*',
                'Cache-Control: no-cache',
                'Connection: keep-alive',
            ])
            ->setPostFields(['postField1' => 1, 'postField2' => 2])
            ->setPause(1, 4)
        ;

        $requestProxyConfig[] = new RequestProxyConfig(false, $curlHelper->exportOptions(), $curlHelper->exportParams());
        $proxyClient          = new RequestProxyClient($path . '/test-api.php', $requestProxyConfig, $token);
        // -- -- --

        // -- Проверяем ответ прокси
        $proxyResult = $proxyClient->execute();
        if (false === $proxyResult) {
            echo $proxyClient->getErrorMessage();

            return;
        }

        $requestProxyResponses = $proxyClient->getContent();
        foreach ($requestProxyResponses as $requestProxyResponse) {
            if ($requestProxyResponse->result) {
                echo 'Ответ на запрос через прокси: ' . PHP_EOL;
                print_r($requestProxyResponse->content);
            } else {
                echo 'Ответ на запрос через прокси с ошибкой: ' . PHP_EOL;
                echo $requestProxyResponse->error . PHP_EOL;
            }
        }
        // -- -- --
    }
}