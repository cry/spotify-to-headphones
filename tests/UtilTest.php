<?php

use phpunit\framework\TestCase;
use CareyLi\s2h;

class UtilTest extends TestCase
{

    public function testcoalesceNull()
    {
        $util = new s2h\Util();

        $result = $util::coalesce(null, 123, "asd");

        $this->assertEquals(123, $result);
    }
}