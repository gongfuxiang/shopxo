<?php

namespace AppTest\Acme;

use App\Acme\Foo;
use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{
    public function testGetName()
    {
        $foo = new Foo();
        $this->assertEquals($foo->getName(), 'Nginx PHP MySQL');
    }
}
