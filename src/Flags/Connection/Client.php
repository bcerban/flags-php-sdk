<?php

namespace Flags\Connection;


use Flags\Exception\ConnectionException;
use Flags\Exception\ConnectionTimeoutException;

class Client
{
    private $baseUrl = 'https://flags-cerban-rodriguez.mybluemix.net/';

    /**
     * @param RequestInterface $request
     * @return Response
     * @throws ConnectionException
     * @throws ConnectionTimeoutException
     */
    public function process(RequestInterface $request) {
        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $this->getRequestUrl($request->getUri()));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        // This option sets the timeout to connect to the remote server
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 3);

        if ($request->getMethod() === RequestInterface::METHOD_POST) {
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($handle, CURLOPT_POSTFIELDS, $request->getBodyAsString());
        } elseif ($request->getMethod() === RequestInterface::METHOD_GET) {
            curl_setopt($handle, CURLOPT_HTTPGET, true);
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'GET');
        }

        curl_setopt($handle, CURLOPT_HTTPHEADER, $this->getRequestHeaders($request->getHeaders()));

        $response = curl_exec($handle);
        $response = preg_replace('/Transfer-Encoding:\s+chunked\r?\n/i', '', $response);

        $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $error = curl_error($handle);
        $errorNumber = curl_errno($handle);

        curl_close($handle);

        if ($errorNumber === CURLE_OPERATION_TIMEDOUT) {
            throw new ConnectionTimeoutException($error);
        }

        if ($error !== '') {
            throw new ConnectionException($error);
        }

        return new Response($responseCode, $response);
    }

    /**
     * @param string $endpoint
     * @return string
     */
    private function getRequestUrl($endpoint = '') {
        return $this->baseUrl . $endpoint;
    }

    /**
     * @param array $headers
     * @return array
     */
    private function getRequestHeaders(array $headers) {
        $mandatoryHeaders = [
            'Content-type: application/json'
        ];

        if (!empty($headers)) {
            $mandatoryHeaders = array_merge($mandatoryHeaders, $headers);
        }

        return $mandatoryHeaders;
    }
}