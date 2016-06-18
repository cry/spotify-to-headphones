<?php

use CareyLi\s2h;

class UtilTest extends \PHPUnit_Framework_TestCase
{

    public function testcoalesceNull()
    {
        $util = new s2h\Util();

        $result = $util::coalesce(null, 123, "asd");

        $this->assertEquals(123, $result);
    }
}