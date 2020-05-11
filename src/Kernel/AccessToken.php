<?php


namespace WorkWeChat\Kernel;


use http\Exception\InvalidArgumentException;
use Overtrue\Http\Exceptions\HttpException;
use Overtrue\Http\Traits\HasHttpRequests;
use WorkWeChat\Kernel\Contracts\AccessTokenInterface;
use WorkWeChat\Kernel\Traits\InteractsWithCache;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AccessToken implements AccessTokenInterface
{

    use HasHttpRequests;
    use InteractsWithCache;

    protected $app;

    protected $requestMethod = 'GET';

    protected $endpointToGetToken;

    protected $queryName;

    protected $token;

    protected $tokenKey = 'access_token';

    protected $cachePrefix = 'workwechat.kernel.access_token.';


    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    public function getRefreshedToken() : array
    {
        return $this->getToken(true);
    }

    public function getToken(bool $refresh = false): array
    {
        $cacheKey = $this->getCacheKey();
        $cache = $this->getCache();

        if( !$refresh && $cache->has($cacheKey) ){
            return $cache->get($cacheKey);
        }

        $token = $this->requestToken($this->getCredentials(), true);

        $this->setToken($token[$this->tokenKey], $token['expires_in'] ?? 7200);

        // TODo $this->app->events->dispatch();
        return $token;
    }

    /**
     * @param string $token
     * @param int $expires
     * @return AccessTokenInterface
     */
    public function setToken(string $token, int $expires = 7200): AccessTokenInterface
    {
        $this->getCache()->set($this->getCacheKey(),[
           $this->tokenKey => $token,
           'expires_in' => $expires,
        ], $expires);

        if( !$this->getCache()->has($this->getCacheKey()) ) {
            throw new \Overtrue\Http\Exceptions\RuntimeException('Failed to cache access token.');
        }

        return $this;
    }

    public function refresh(): AccessTokenInterface
    {
        $this->getToken(true);

        return $this;
    }


    public function requestToken(array $credentials, $toArray = false)
    {
        $response = $this->sendRequest($credentials);
        $result = json_decode($response->getBody()->getContents(), true);
        $formatted = $this->castResponseToType($response, $this->app['config']->get('response_type'));
        if (empty($result[$this->tokenKey])) {
            throw new HttpException('Request access_token fail: '.json_encode($result, JSON_UNESCAPED_UNICODE), $response, $formatted);
        }

        return $toArray ? $result : $formatted;
    }

    protected function sendRequest(array $credentials): ResponseInterface
    {
        $options = [
            ('GET' === $this->requestMethod) ? 'query' : 'json' => $credentials,
        ];

        return $this->setHttpClient($this->app['http_client'])->request($this->getEndpoint(), $this->requestMethod, $options);
    }

    public function getTokenKey()
    {
        return $this->tokenKey;
    }

    public function getEndpoint(): string
    {
        if (empty($this->endpointToGetToken)) {
            throw new \Psr\Log\InvalidArgumentException('No endpoint for access token request.');
        }

        return $this->endpointToGetToken;
    }

    protected function getCacheKey()
    {
        return $this->cachePrefix.md5(json_encode($this->getCredentials()));
    }

    abstract protected function getCredentials(): array;

}