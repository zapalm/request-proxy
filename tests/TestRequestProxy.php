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
 * @author Maksim T. <zapalm@yandex.com>
 */
class TestRequestProxy extends PHPUnit_Framework_TestCase
{
    /**
     * Тест обработки запроса проксирующим сервисом на примере 1 (/examples/1).
     */
    public function testRequestByExample1()
    {
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
                'Connection: Keep-Alive',
                'Host: ' . $host,
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
            ->setReferer('http://localhost/')
            ->setHttpHeader([
                'Accept: */*',
                'Cache-Control: no-cache',
                'Connection: Keep-Alive',
                'Host: ' . $host,
                'Proxy-Connection: keep-alive',
            ])
            ->setPostFields(['postField1' => 1, 'postField2' => 2])
            ->setPause(1, 4)
        ;

        $requestProxyConfig[] = new RequestProxyConfig(false, $curlHelper->exportOptions(), $curlHelper->exportParams());
        $proxyClient          = new RequestProxyClient($path . '/index.php', $requestProxyConfig, $token);
        // -- -- --

        // -- Проверяем ответ прокси
        $proxyResult = $proxyClient->execute();
        if (false === $proxyResult) {
            echo implode(PHP_EOL, $proxyClient->getErrors());

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