<?php

namespace SimpleSAML\Test\Auth;

use SimpleSAML\Auth\SourceFactory;
use SimpleSAML\Test\Utils\ClearStateTestCase;

/**
 * Tests for \SimpleSAML\Auth\Source
 */
class SourceTest extends ClearStateTestCase
{
    /**
     * @return void
     */
    public function testParseAuthSource()
    {
        $class = new \ReflectionClass(\SimpleSAML\Auth\Source::class);
        $method = $class->getMethod('parseAuthSource');
        $method->setAccessible(true);

        // test direct instantiation of the auth source object
        $authSource = $method->invokeArgs(null, ['test', [TestAuthSource::class]]);
        $this->assertInstanceOf(TestAuthSource::class, $authSource);

        // test instantiation via an auth source factory
        $authSource = $method->invokeArgs(null, ['test', [TestAuthSourceFactory::class]]);
        $this->assertInstanceOf(TestAuthSource::class, $authSource);
    }
}

class TestAuthSource extends \SimpleSAML\Auth\Source
{
    /**
     * @return void
     */
    public function authenticate(array &$state)
    {
    }
}

class TestAuthSourceFactory implements SourceFactory
{
    /**
     * @return \SimpleSAML\Test\Auth\TestAuthSource
     */
    public function create(array $info, array $config)
    {
        return new TestAuthSource($info, $config);
    }
}
