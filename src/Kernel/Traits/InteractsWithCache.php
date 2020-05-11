<?php


namespace WorkWeChat\Kernel\Traits;



use http\Exception\InvalidArgumentException;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Contracts\Cache\CacheInterface;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use WorkWeChat\Kernel\ServiceContainer;


trait InteractsWithCache
{

    protected $cache;


    public function getCache()
    {
        if ($this->cache) {
            return $this->cache;
        }

        if (property_exists($this, 'app') && $this->app instanceof ServiceContainer && isset($this->app['cache'])) {
            $this->setCache($this->app['cache']);

            // Fix PHPStan error
            assert($this->cache instanceof CacheInterface);

            return $this->cache;
        }

        return $this->cache = $this->createDefaultCache();
    }

    public function setCache($cache)
    {
        if (empty(\array_intersect([SimpleCacheInterface::class, CacheItemPoolInterface::class], \class_implements($cache)))) {
            throw new \Psr\Log\InvalidArgumentException(\sprintf('The cache instance must implements %s or %s interface.', SimpleCacheInterface::class, CacheItemPoolInterface::class));
        }

        if ($cache instanceof CacheItemPoolInterface) {
            if (!$this->isSymfony43OrHigher()) {
                throw new \Psr\Log\InvalidArgumentException(sprintf('The cache instance must implements %s', SimpleCacheInterface::class));
            }
            $cache = new Psr16Cache($cache);
        }

        $this->cache = $cache;

        return $this;
    }

    protected function createDefaultCache()
    {
        if ($this->isSymfony43OrHigher()) {
            return new Psr16Cache(new FilesystemAdapter('work-wechat', 1500));
        }
        //return new FilesystemCache();
    }

    protected function isSymfony43OrHigher(): bool
    {
        return \class_exists('Symfony\Component\Cache\Psr16Cache');
    }


}