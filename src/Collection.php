<?php
namespace Yriveiro\Collection;

use Closure;
use ArrayIterator;
use Yriveiro\Collection\CollectionInterface;

class Collection implements CollectionInterface
{
    /**
     * @var array An array containing all items in collection
     */
    protected $items = array();

    public function __construct(array $items = array())
    {
        $this->items = $items;
    }

    /**
     * Set collection item
     *
     * @param string $key
     * @param mixed|null $value
     *
     * @return Yriveiro\Collection\Collection
     */
    public function set($key, $value = null)
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Get collection item for key
     *
     * @param string $key    The key
     * @param mixed  $default The default value in case key does not exists.
     *
     * @return mixed The key value, otherwise, the default value.
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->items[$key] : $default;
    }

    /**
     * Get all items in collection
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Get all keys in collection
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->all());
    }

    /**
     * Removes an item from collection
     *
     * @param string $key
     *
     * @return Yriveiro\Collection\Collection
     */
    public function remove($key)
    {
        unset($this->items[$key]);

        return $this;
    }

    /**
     *  Remove all items in the collection.
     *
     * @return Yriveiro\Collection\Collection
     */
    public function clear()
    {
        $this->items = array();

        return $this;
    }

    /**
     * Does key exists in collection?
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Does key exists in collection?
     *
     * @param $key
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * Apply the specified function to each item in the collection.
     *
     * @param \Closure $closure The function to apply to each item.
     *                          The callable format must be arguments injected
     *                          are: [$key, $item]
     *
     * @return Yriveiro\Collection\Collection
     */
    public function each(Closure $callable)
    {
        foreach ($this->items as $key => &$item) {
            $callable($key, $item);
        }

        return $this;
    }

    /**
     * Return the keys with its values that match the pattern.
     *
     * @param string $pattern
     *
     * @return mixed
     */
    public function match($pattern)
    {
        $self = $this;
        $keys = $this->keys();
        $pattern = trim($pattern, '/');

        return array_reduce($keys, function ($carry, $key) use ($pattern, $self) {
            if (preg_match("/$pattern/", $key)) {
                $carry[$key] = $self->get($key);
            }

            return $carry;
        }, array());
    }

    /**
     * Does this collection have a given key?
     *
     * @param  string $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get collection item for key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set collection item
     *
     * @param string $key
     * @param mixed  $value
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Remove item from collection
     *
     * @param string $key
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * Get number of items in collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get collection iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
