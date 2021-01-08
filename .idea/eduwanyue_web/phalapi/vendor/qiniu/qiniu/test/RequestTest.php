<?php
/**
 * RequestTest.php.
 */

namespace Qiniu;


class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    protected $request;

    public function setUp()
    {
        $this->request = new Request();
    }

    public function testDefaultOptions()
    {
        $this->assertEquals(array(
            'method'   => 'GET',
            'url'      => null,
            'headers'  => array(),
            'body'     => '',
            'form'     => array(),
            'files'    => array(),
            'encoding' => 'utf-8',
            'timeout'  => 60
        ), $this->request->options);
    }
}
