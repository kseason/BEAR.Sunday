<?php

/**
 * @package BEAR.Framework
 */
namespace BEAR\Framework;

use Ray\Di\Definition;
use Ray\Di\Annotation;
use Ray\Di\Config;
use Ray\Di\Forge;
use Ray\Di\Container;
use Ray\Di\Injector;
use Ray\Di\InjectorInterface as Inject;
use Ray\Di\AbstractModule;
use BEAR\Framework\Module\StandardModule as FrameWorkModule;
use BEAR\Framework\Web\Outputtable;

/**
 * Application context
 *
 * @package BEAR.Framework
 * @author  Akihito Koriyama <akihito.koriyama@gmail.com>
 */
abstract class AbstractAppContext
{
    /**
     * Application Version
     *
     * @var string
     */
    const VERSION = '0.0.0';
    /**
     * Application name
     *
     * @var string
     */
    public $name;

    /**
     * Application porject root path
     *
     * @var string
     */
    public $path;

    /**
     * Dependency injector
     *
     * @var Ray\Di\Injector
     */
    public $di;

    /**
     * Resource client
     *
     * @var BEAR\Resource\Client
     */
    public $resource;


    /**
     * Constructor
     *
     * @param Framework $framework
     */
    public function __construct(array $appModules = [], Framework $framework)
    {
        $this->framework = $framework;
        list($this->di, $this->resource) = $this->framework->getApplicationProperties($appModules, $this);
    }

    /**
     * Annotation Settings
     *
     */
    protected $annotations = [
        'provides' => 'BEAR\Resource\Annotation\Provides',
        'signal' => 'BEAR\Resource\Annotation\Signal',
        'argsignal' => 'BEAR\Resource\Annotation\ParamSignal',
        'get' => 'BEAR\Resource\Annotation\Get',
        'post' => 'BEAR\Resource\Annotation\Post',
        'put' => 'BEAR\Resource\Annotation\Put',
        'delete' => 'BEAR\Resource\Annotation\Delete',
    ];

    /**
     * to string
     */
    public function __toString()
    {
        return $this->name . self::VERSION;
    }
}