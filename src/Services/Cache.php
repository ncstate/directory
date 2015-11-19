<?php

namespace NCState\Services;

use Closure;

class Cache
{
    protected $prefix;

    public function __construct($prefix = 'pc_')
    {
        $this->prefix = $prefix;
    }

    /**
     * Checks the cache for a specific item.
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->get($key) !== null;
    }

    /**
     * Retrieves a value from the cache.
     *
     * @param string $key Key representing where value is stored.
     * @return mixed|null returns value if key exists and is current, null otherwise.
     */
    public function get($key)
    {
        $value = get_transient($this->prefix.$key);

        if ($value === false) {
            return null;
        }

        return $value;
    }

    /**
     * Stores an item in the cache for some amount of time.
     *
     * @param string $key
     * @param mixed $item
     * @param int $minutes
     * @return bool
     */
    public function put($key, $item, $minutes = 1)
    {
        return set_transient($this->prefix.$key, $item, $minutes * 60);
    }

    /**
     * Remembers the return value of a closure for some amount of time.
     *
     * @param string $key
     * @param int $minutes
     * @param Closure $callback
     * @return mixed
     */
    public function remember($key, $minutes, Closure $callback)
    {
        if (!is_null($value = $this->get($key))) {
            return $value;
        }
        $this->put($key, $value = $callback(), $minutes);
        return $value;
    }

    /**
     * Stores an item in the cache with an unlimited time-to-live.
     *
     * @param $key
     * @param $item
     * @return bool
     */
    public function forever($key, $item)
    {
        return set_transient($this->prefix.$key, $item, 0);
    }

    /**
     * Removes an item from the cache.
     *
     * @param $key
     * @return bool
     */
    public function forget($key)
    {
        return delete_transient($this->prefix.$key);
    }

    /**
     * Flushes stale items out of cache.
     *
     * @param bool $hard if true, delete old transients rather than invalidate them by WordPress means.
     */
    public function flush($hard = false)
    {
        global $wpdb;

        $transients = $wpdb->get_col(
            $wpdb->prepare("
                SELECT REPLACE(option_name, '_transient_timeout_', '') AS transient_name
                FROM {$wpdb->options}
                WHERE option_name LIKE '\_transient\_timeout\__%%'
            ", array())
        );

        foreach ($transients as $transient) {
            if ($hard === true) {
                delete_transient($transient);
            } else {
                get_transient($transient);
            }
        }
    }

    public function getPrefix()
    {
        return $this->prefix;
    }
}
