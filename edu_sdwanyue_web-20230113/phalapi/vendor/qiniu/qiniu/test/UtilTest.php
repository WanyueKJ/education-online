<?php
/**
 * UtilTest.php.
 */

namespace Qiniu;


class UtilTest extends \PHPUnit_Framework_TestCase
{
    public function testUriEncode()
    {
        $this->assertEquals('Tm8rQ29kZS90ZXN0', Util::uriEncode('No+Code/test'));
    }
}
