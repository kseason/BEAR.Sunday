<?php
/**
 *  BEAR.Framework
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Framework;

use BEAR\Resource\Resource;
use Ray\Di\Definition;
use Ray\Di\Annotation;
use Ray\Di\Config;
use Ray\Di\Forge;
use Ray\Di\Container;
use Ray\Di\Injector;
use Ray\Di\InjectorInterface as Inject;
use BEAR\Framework\Router;
use BEAR\Framework\DevRouter;
use BEAR\Framework\Exception\NotFound;
use BEAR\Framework\AbstractAppContext as AppContext;
use Aura\Autoload\Exception\NotReadable;

use Doctrine\Common\Cache\MemcacheCache as Cache;
use Guzzle\Common\Cache\DoctrineCacheAdapter as CacheAdapter;

/**
 * Dispatcher
 *
 * @package BEAR.Framework
 * @author  Akihito Koriyama <akihito.koriyama@gmail.com>
 */
final class Dispatcher
{
    /**
     * Application context
     *
     * @var AppContext
     */
    private $app;

    /**
     * System path
     *
     * @var string
     */
    private $systemPath;

    /**
     * Constructor
     *
     * @param string $appName
     * @param string $appPath
     */
    public function __construct(AppContext $app, Cache $cache = null)
    {
        $this->app = $app;
        $this->cache = $cache;
        $this->systemPath = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
    }

    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get instance
     *
     * @param string $pageUri Page resource path ("/hello/world")
     *
     * @return array [BEAR\Resource\Resource $resource, BEAR\Resource\Object $page]
     *
     * @throws Exception
     */
    public function getInstance($pageUri)
    {
        if ($this->cache && $this->cached) {
            list($resource, $page) = unserialize($cached);
        }
        $resource = $this->app->resource;
        try {
            $page = $resource->newInstance($pageUri);
        } catch (NotReadable $e) {
            try {
                $page = $resource->newInstance($pageUri . 'index');
            } catch (NotReadable $e) {
                throw new Exception\ResourceNotFound($pageUri, 404, $e);
            }
        } catch (\Exception $e) {
            throw $e;
        }
        if ($this->cache) {
            $this->cache->save($key, serialize([$resource, $page]));
        }
        // serializable test
        $page = unserialize(serialize($page));
        return [$resource, $page];
    }
}
