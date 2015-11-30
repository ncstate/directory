<?php

namespace NCState\Services;

use Mockery as m;

/**
 * Set/Update the value of a transient;
 *
 * @param $transient
 * @param $value
 * @param $expiration
 *
 * @return boolean True if successful. False, otherwise.
 */
function set_transient($transient, $value, $expiration)
{
    return CacheTest::$functions->set_transient($transient, $value, $expiration);
}

/**
 * Get the value of a transient
 *
 * @param $transient
 *
 * @return mixed Value of transient if exists. False, otherwise.
 */
function get_transient($transient)
{
    return CacheTest::$functions->get_transient($transient);
}

/**
 * Delete a transient.
 *
 * @param $transient
 *
 * @return boolean True if successful. False, otherwise.
 */
function delete_transient($transient)
{
    return CacheTest::$functions->delete_transient($transient);
}

class CacheTest extends \PHPUnit_Framework_TestCase
{
    public static $functions;
    public static $wpdb;
    public $cache;

    public function setUp()
    {
        global $wpdb;

        self::$functions = m::mock();
        self::$wpdb = $wpdb = m::mock();
        $wpdb->options = '';

        $this->cache = new Cache();
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_works_and_has_default_prefix()
    {
        $this->assertNotEmpty($this->cache->getPrefix());
    }

    /** @test */
    public function it_returns_value_if_exists_in_cache()
    {
        $key = $this->cache->getPrefix() . 'foo';

        self::$functions->shouldReceive('get_transient')
            ->with($key)
            ->once()
            ->andReturn('bar');

        $output = $this->cache->get('foo');

        $this->assertEquals('bar', $output);
    }

    /** @test */
    public function it_returns_null_if_does_not_exist_in_cache()
    {
        $key = $this->cache->getPrefix() . 'foo';

        /*
         * This mock covers the case that a transient doesn't exist,
         * does not have a value, or has expired.
         */
        self::$functions->shouldReceive('get_transient')
            ->with($key)
            ->once()
            ->andReturn(false);

        $output = $this->cache->get('foo');

        $this->assertNull($output);
    }

    /** @test */
    public function it_can_tell_if_a_value_exists_for_cache_key()
    {
        $key = $this->cache->getPrefix() . 'foo';

        self::$functions->shouldReceive('get_transient')
            ->with($key)
            ->once()
            ->andReturn(false);

        $this->assertFalse($this->cache->has('foo'));

        self::$functions->shouldReceive('get_transient')
            ->with($key)
            ->once()
            ->andReturn('bar');

        $this->assertTrue($this->cache->has('foo'));
    }

    /** @test */
    public function it_can_store_a_value_in_the_cache_with_an_expiration()
    {
        self::$functions->shouldReceive('set_transient')
            ->with($this->cache->getPrefix() . 'foo', 'bar', 300)
            ->once()
            ->andReturn(true);

        $output = $this->cache->put('foo', 'bar', 5);

        $this->assertTrue($output);
    }

    /** @test */
    public function it_can_store_a_value_in_the_cache_with_default_expiration()
    {
        self::$functions->shouldReceive('set_transient')
        ->with($this->cache->getPrefix() . 'foo', 'bar', 60)
        ->once()
        ->andReturn(true);

        $this->cache->put('foo', 'bar');
    }

    /** @test */
    public function it_returns_false_when_cached_value_is_not_stored()
    {
        self::$functions->shouldReceive('set_transient')
            ->with($this->cache->getPrefix() . 'foo', 'bar', 300)
            ->once()
            ->andReturn(false);

        $output = $this->cache->put('foo', 'bar', 5);

        $this->assertFalse($output);
    }

    /** @test */
    public function it_can_store_a_value_in_the_cache_forever()
    {
        self::$functions->shouldReceive('set_transient')
            ->with($this->cache->getPrefix() . 'foo', 'bar', 0)
            ->once()
            ->andReturn(true);

        $output = $this->cache->forever('foo', 'bar');

        $this->assertTrue($output);
    }

    /** @test */
    public function it_can_manually_remove_a_cached_value()
    {
        self::$functions->shouldReceive('delete_transient')
            ->with($this->cache->getPrefix() . 'foo')
            ->once()
            ->andReturn(true);

        $output = $this->cache->forget('foo');

        $this->assertTrue($output);
    }

    /** @test */
    public function it_returns_false_when_cached_value_could_not_be_removed()
    {
        self::$functions->shouldReceive('delete_transient')
            ->with($this->cache->getPrefix() . 'foo')
            ->once()
            ->andReturn(false);

        $output = $this->cache->forget('foo');

        $this->assertFalse($output);
    }

    /** @test */
    public function it_can_remove_all_transients()
    {
        self::$wpdb->shouldReceive('prepare')
            ->times(2)
            ->andReturn('sql statement');

        self::$wpdb->shouldReceive('get_col')
            ->times(2)
            ->andReturn(array($this->cache->getPrefix().'sample'));

        self::$functions->shouldReceive('get_transient')
            ->with($this->cache->getPrefix().'sample')
            ->once()
            ->andReturn(true);

        self::$functions->shouldReceive('delete_transient')
            ->with($this->cache->getPrefix().'sample')
            ->once()
            ->andReturn(true);

        $this->cache->flush();
        $this->cache->flush($hard = true);
    }
}