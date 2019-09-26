<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace IYin\CloudSign\Kernel;

use IYin\CloudSign\Kernel\Contracts\AccessTokenInterface;
use IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException;
use IYin\CloudSign\Kernel\Http\Response;
use IYin\CloudSign\Kernel\Support\Arr;
use IYin\CloudSign\Kernel\Support\Str;
use IYin\CloudSign\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseClient.
 *
 * @author overtrue <i@overtrue.me>
 */
class BaseClient
{
    use HasHttpRequests { request as performRequest; }

    /**
     * @var \IYin\CloudSign\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var \IYin\CloudSign\Kernel\Contracts\AccessTokenInterface
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $baseUri;

    /**
     * @var bool
     */
    protected $raw = false;

    /**
     * BaseClient constructor.
     *
     * @param \IYin\CloudSign\Kernel\ServiceContainer                    $app
     * @param \IYin\CloudSign\Kernel\Contracts\AccessTokenInterface|null $accessToken
     */
    public function __construct(ServiceContainer $app, AccessTokenInterface $accessToken = null)
    {
        $this->app = $app;
        $this->accessToken = $accessToken ?? $this->app['access_token'];
        $this->baseUri = $this->app->config->get('http.base_uri');
    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array  $query
     * @param array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface |\IYin\CloudSign\Kernel\Support\Collection|array|object|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     */
    public function httpGet(string $url, array $query = [], array $data = [])
    {
        return $this->request($url, 'GET', ['query' => $query, 'json' => $data]);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface |\IYin\CloudSign\Kernel\Support\Collection|array|object|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     */
    public function httpPost(string $url, array $data = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data]);
    }

    /**
     * JSON request.
     *
     * @param string       $url
     * @param string|array $data
     * @param array        $query
     *
     * @return \Psr\Http\Message\ResponseInterface |\IYin\CloudSign\Kernel\Support\Collection|array|object|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     */
    public function httpPostJson(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'json' => $data]);
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array  $files
     * @param array  $form
     * @param array  $query
     *
     * @return \Psr\Http\Message\ResponseInterface |\IYin\CloudSign\Kernel\Support\Collection|array|object|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     */
    public function httpUpload(string $url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', ['query' => $query, 'multipart' => $multipart, 'connect_timeout' => 30, 'timeout' => 30, 'read_timeout' => 30]);
    }

    /**
     * Upload file, header with token.
     *
     * @param string $url
     * @param array  $files
     * @param array  $form
     * @param array  $headers
     *
     * @return array|Support\Collection|object|ResponseInterface|string
     *
     * @throws Exceptions\InvalidConfigException
     */
    public function httpUploadWithToken(string $url, array $files, array $form = [], array $headers = [])
    {
        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', ['multipart' => $multipart, 'headers' => $headers, 'connect_timeout' => 30, 'timeout' => 30, 'read_timeout' => 30]);
    }

    /**
     * @return AccessTokenInterface
     */
    public function getAccessToken(): AccessTokenInterface
    {
        return $this->accessToken;
    }

    /**
     * @param \IYin\CloudSign\Kernel\Contracts\AccessTokenInterface $accessToken
     *
     * @return $this
     */
    public function setAccessToken(AccessTokenInterface $accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     * @param bool   $returnRaw
     *
     * @return array|Support\Collection|object|ResponseInterface|string
     *
     * @throws Exceptions\InvalidConfigException
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);

        return ($returnRaw || $this->raw) ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     *
     * @return \IYin\CloudSign\Kernel\Http\Response
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     */
    public function requestRaw(string $url, string $method = 'GET', array $options = [])
    {
        return Response::buildFromPsrResponse($this->request($url, $method, $options, true));
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        // retry
        $this->pushMiddleware($this->retryMiddleware(), 'retry');
        // access token
        $this->pushMiddleware($this->accessTokenMiddleware(), 'access_token');
        // log
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * Attache access token to request query.
     *
     * @return \Closure
     */
    protected function accessTokenMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if ($this->accessToken) {
                    $request = $this->accessToken->applyToRequest($request, $options);
                }

                return $handler($request, $options);
            };
        };
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);

        return Middleware::log($this->app['logger'], $formatter);
    }

    /**
     * Return retry middleware.
     *
     * @return \Closure
     */
    protected function retryMiddleware()
    {
        return Middleware::retry(function (
            $retries,
            RequestInterface $request,
            ResponseInterface $response = null
        ) {
            // Limit the number of retries to 2
            if ($retries < $this->app->config->get('http.max_retries', 1) && $response && $body = $response->getBody()) {
                // Retry on server errors
                $response = json_decode($body, true);

                if (isset($response['code']) && in_array(abs($response['code']), [40001, 500, 10006], true)) {
                    $this->accessToken->refresh();
                    $this->app['logger']->debug('Retrying with refreshed access token.');

                    return true;
                }
            }

            return false;
        }, function () {
            return abs($this->app->config->get('http.retry_delay', 500));
        });
    }

    /**
     * Get format query params.
     *
     * @param array $data
     *
     * @return array
     *
     * @throws Exceptions\RuntimeException
     * @throws InvalidArgumentException
     */
    protected function formatQueryWithSign($data = []): array
    {
        $token = $this->accessToken->getAccessToken();
        $timestamp = (string) floor(microtime(true) * 1000);
        $nonce_str = Str::random(10);
        $sign_type = $this->app->config->get('sign_type');
        $signArray = compact('token', 'timestamp', 'nonce_str', 'sign_type');
        $array = array_merge($signArray, $data);
        $array = array_filter($array);

        $array['sign'] = $this->signature($array, $sign_type);
        ksort($array, SORT_STRING);

        return $array;
    }

    /**
     * @param array $data
     * @param array $required
     *
     * @throws InvalidArgumentException
     */
    protected function verificationRequired(array $data, array $required = [])
    {
        if (empty($required)) {
            return;
        }

        foreach ($required as $filed) {
            $v = Arr::get($data, $filed, null);
            if (null === $v) {
                throw new InvalidArgumentException(sprintf('Attribute "%s" is required!', $filed));
            } else {
                if (empty($v)) {
                    throw new InvalidArgumentException(sprintf('Attribute "%s" can not be empty!', $filed));
                }
            }
        }
    }

    /**
     * 参数参与签名.
     *
     * @param array  $data
     * @param string $sign_type
     *
     * @return string
     */
    protected function signature(array $data, $sign_type)
    {
        ksort($data, SORT_STRING);
        $str = '';
        foreach ($data as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $v = is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $v;
            $str .= $k.'='.$v.'&';
        }

        $key = $this->app->config->get('secret');
        $str .= 'key='.$key;
        $sign_type = isset($data['sign_type']) ? $data['sign_type'] : $sign_type;

        if ('HMAC-SHA256' === ($sign_type = $sign_type ?? 'MD5')) {
            return strtoupper(hash_hmac('sha256', $str, $key));
        } else {
            return strtoupper(hash($sign_type, $str));
        }

    }

    /**
     * 获取原始数据.
     *
     * @param bool $raw
     *
     * @return $this
     */
    public function raw($raw = true)
    {
        $this->raw = $raw;

        return $this;
    }
}
