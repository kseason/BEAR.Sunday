<?php
namespace helloworld\Resource\Page;

use BEAR\Resource\Object as ResourceObject;
use BEAR\Resource\AbstractObject as Page;

/**
 * Hello World
 */
class Hello extends Page
{
    /**
     * @return self
     */
    public function onGet($name)
    {
        $this->body = 'Hello ' . $name;
        return $this;
    }
}
