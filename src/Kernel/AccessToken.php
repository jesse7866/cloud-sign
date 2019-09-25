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
use IYin\CloudSign\Kernel\Exceptions\HttpException;
use IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException;
use IYin\CloudSign\Kernel\Exceptions\RuntimeException;
use IYin\CloudSign\Kernel\Support\Arr;
use IYin\CloudSign\Kernel\Traits\HasHttpRequests;
use IYin\CloudSign\Kernel\Traits\InteractsWithCache;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AccessToken.
 *
 * @author overtrue <i@overtrue.me>
 */
abstract class AccessToken implements AccessTokenInterface
{
    use HasHttpRequests, InteractsWithCache;

    /**
     * @var \Pimple\Container
     */
    protected $app;

    /**
     * @var string
     */
    protected $requestMethod = 'POST';

    /**
     * @var string
     */
    protected $endpointToGetToken;

    /**
     * @var string
     */
    protected $queryName;

    /**
     * @var array
     */
    protected $token;

    /**
     * @var int
     */
    protected $safeSeconds = 500;

    /**
     * @var int
     */
    protected $maxExpiresIn = 60 * 60 * 12;

    /**
     * @var string
     */
    protected $tokenKey = 'access_token';

    /**
     * @var string
     */
    protected $refreshTokenKey = 'refresh_token';

    /**
     * @var string
     */
    protected $cachePrefix = 'cloudsign.kernel.access_token.';

    /**
     * AccessToken constructor.
     *
     * @param \Pimple\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @return array
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function getRefreshedToken(): array
    {
        return $this->getToken(true);
    }

    /**
     * 获取 Token
     *
     * @param bool $refresh
     *
     * @return array
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function getToken(bool $refresh = false): array
    {
        $cacheKey = $this->getCacheKey();
        $cache = $this->getCache();
        $credentials = $this->getCredentials();

        if (!$refresh && $cache->has($cacheKey)) {
            return $cache->get($cacheKey);
        }

        if ($refresh && $cache->has($cacheKey)) {
            $credentials = array_merge($credentials, ['grant_type' => 'refresh_token']);
            $refreshToken = Arr::get($cache->get($cacheKey), $this->refreshTokenKey);
            $credentials = array_merge($credentials, [$this->refreshTokenKey => $refreshToken]);
        }

        $token = $this->requestToken($credentials, true);

        $this->setToken(
            Arr::only($token, [$this->tokenKey, $this->refreshTokenKey]),
            $token['expires_in'] ?? $this->maxExpiresIn
        );

        return $token;
    }

    /**
     * @param bool $refresh
     *
     * @return string
     *
     * @throws Exceptions\InvalidConfigException
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getAccessToken(bool $refresh = false): string
    {
        return $this->getToken($refresh)[$this->tokenKey];
    }

    /**
     * @param string $token
     * @param int    $lifetime
     *
     * @return \IYin\CloudSign\Kernel\Contracts\AccessTokenInterface
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function setToken(array $token, int $lifetime = 0): AccessTokenInterface
    {
        $lifetime = $lifetime ?? $this->maxExpiresIn;
        $this->getCache()->set(
            $this->getCacheKey(),
            array_merge($token, ['expires_in' => $lifetime,]),
            $lifetime - $this->safeSeconds);

        if (!$this->getCache()->has($this->getCacheKey())) {
            throw new RuntimeException('Failed to cache access token.');
        }

        return $this;
    }

    /**
     * @return \IYin\CloudSign\Kernel\Contracts\AccessTokenInterface
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function refresh(): AccessTokenInterface
    {
        $this->getToken(true);

        return $this;
    }

    /**
     * @param array $credentials
     * @param bool  $toArray
     *
     * @return \Psr\Http\Message\ResponseInterface|\Iyin\CloudSign\Kernel\Support\Collection|array|object|string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\HttpException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     */
    public function requestToken(array $credentials, $toArray = false)
    {
        $response = $this->sendRequest($credentials);
        $result = json_decode($response->getBody()->getContents(), true);
        $formatted = $this->castResponseToType($response, $this->app['config']->get('response_type'));

        if (isset($result['code']) && $result['code'] !== 0 && empty($result['data'][$this->tokenKey])) {
            throw new HttpException('Request access_token fail: '.json_encode($result, JSON_UNESCAPED_UNICODE), $response, $formatted);
        }

        return $toArray ? $result['data'] : $formatted;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array                              $requestOptions
     *
     * @return \Psr\Http\Message\RequestInterface
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    public function applyToRequest(RequestInterface $request, array $requestOptions = []): RequestInterface
    {
        parse_str($request->getUri()->getQuery(), $query);

        $query = http_build_query(array_merge($this->getQuery(), $query));

        return $request->withUri($request->getUri()->withQuery($query));
    }

    /**
     * Send http request.
     *
     * @param array $credentials
     *
     * @return ResponseInterface
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     */
    protected function sendRequest(array $credentials): ResponseInterface
    {
        $options = [
            ('GET' === $this->requestMethod) ? 'query' : 'json' => $credentials,
        ];

        return $this->setHttpClient($this->app['http_client'])->request($this->getEndpoint(), $this->requestMethod, $options);
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cachePrefix.md5(json_encode($this->getCredentials()));
    }

    /**
     * The request query will be used to add to the request.
     *
     * @return array
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidConfigException
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     * @throws \IYin\CloudSign\Kernel\Exceptions\RuntimeException
     */
    protected function getQuery(): array
    {
        return [$this->queryName];
    }

    /**
     * @return string
     *
     * @throws \IYin\CloudSign\Kernel\Exceptions\InvalidArgumentException
     */
    public function getEndpoint(): string
    {
        if (empty($this->endpointToGetToken)) {
            throw new InvalidArgumentException('No endpoint for access token request.');
        }

        return $this->endpointToGetToken;
    }

    /**
     * @return string
     */
    public function getTokenKey()
    {
        return $this->tokenKey;
    }

    /**
     * Credential for get token.
     *
     * @return array
     */
    abstract protected function getCredentials(): array;
}
