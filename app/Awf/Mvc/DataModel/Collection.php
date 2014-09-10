<?php
/**
 * @package		awf
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 *
 * Based on Laravel 4's Illuminate\Database\Eloquent\Collection
 *
 * Laravel 4 is distributed under the MIT license, see https://github.com/laravel/framework/blob/master/LICENSE.txt
 */

namespace Awf\Mvc\DataModel;

use Awf\Mvc\DataModel;
use Awf\Utils\Collection as BaseCollection;

/**
 * A collection of data models. You can enumerate it like an array, use it everywhere a collection is expected (e.g. a
 * foreach loop) and even implements a countable interface. You can also batch-apply DataModel methods on it thanks to
 * its magic __call() method, hence the type-hinting below.
 *
 * @method void setFieldValue(string $name, mixed $value = '')
 * @method void archive()
 * @method void save(mixed $data, string $orderingFilter = '', bool $ignore = null)
 * @method void push(mixed $data, string $orderingFilter = '', bool $ignore = null, array $relations = null)
 * @method void bind(mixed $data, array $ignore = array())
 * @method void check()
 * @method void reorder(string $where = '')
 * @method void delete(mixed $id = null)
 * @method void trash(mixed $id)
 * @method void forceDelete(mixed $id = null)
 * @method void lock(int $userId = null)
 * @method void move(int $delta, string $where = '')
 * @method void publish()
 * @method void restore(mixed $id)
 * @method void touch(int $userId = null)
 * @method void unlock()
 * @method void unpublish()
 *
 * @package Awf\Mvc\DataModel
 */
class Collection extends BaseCollection
{
	/**
	 * Find a model in the collection by key.
	 *
	 * @param  mixed  $key
	 * @param  mixed  $default
	 *
	 * @return DataModel
	 */
	public function find($key, $default = null)
	{
		if ($key instanceof DataModel)
		{
			$key = $key->getId();
		}

		return array_first($this->items, function($itemKey, $model) use ($key)
		{
			return $model->getId() == $key;

		}, $default);
	}

	public function removeById($key)
	{
		if ($key instanceof DataModel)
		{
			$key = $key->getId();
		}

		$index = array_search($key, $this->modelKeys());

		if ($index !== false)
		{
			unset($this->items[$index]);
		}
	}

	/**
	 * Add an item to the collection.
	 *
	 * @param  mixed  $item
	 *
	 * @return Collection
	 */
	public function add($item)
	{
		$this->items[] = $item;

		return $this;
	}

	/**
	 * Determine if a key exists in the collection.
	 *
	 * @param  mixed  $key
	 *
	 * @return bool
	 */
	public function contains($key)
	{
		return ! is_null($this->find($key));
	}

	/**
	 * Fetch a nested element of the collection.
	 *
	 * @param  string  $key
	 *
	 * @return Collection
	 */
	public function fetch($key)
	{
		return new static(array_fetch($this->toArray(), $key));
	}

	/**
	 * Get the max value of a given key.
	 *
	 * @param  string  $key
	 *
	 * @return mixed
	 */
	public function max($key)
	{
		return $this->reduce(function($result, $item) use ($key)
		{
			return (is_null($result) || $item->{$key} > $result) ? $item->{$key} : $result;
		});
	}

	/**
	 * Get the min value of a given key.
	 *
	 * @param  string  $key
	 *
	 * @return mixed
	 */
	public function min($key)
	{
		return $this->reduce(function($result, $item) use ($key)
		{
			return (is_null($result) || $item->{$key} < $result) ? $item->{$key} : $result;
		});
	}

	/**
	 * Get the array of primary keys
	 *
	 * @return array
	 */
	public function modelKeys()
	{
		return array_map(function($m) { return $m->getId(); }, $this->items);
	}

	/**
	 * Merge the collection with the given items.
	 *
	 * @param  BaseCollection|array  $items
	 *
	 * @return BaseCollection
	 */
	public function merge($collection)
	{
		$dictionary = $this->getDictionary($this);

		foreach ($collection as $item)
		{
			$dictionary[$item->getId()] = $item;
		}

		return new static(array_values($dictionary));
	}

	/**
	 * Diff the collection with the given items.
	 *
	 * @param   BaseCollection|array  $items
	 *
	 * @return  BaseCollection
	 */
	public function diff($collection)
	{
		$diff = new static;

		$dictionary = $this->getDictionary($collection);

		foreach ($this->items as $item)
		{
			if ( ! isset($dictionary[$item->getId()]))
			{
				$diff->add($item);
			}
		}

		return $diff;
	}

	/**
	 * Intersect the collection with the given items.
	 *
	 * @param   BaseCollection|array  $items
	 *
	 * @return  Collection
	 */
	public function intersect($collection)
	{
		$intersect = new static;

		$dictionary = $this->getDictionary($collection);

		foreach ($this->items as $item)
		{
			if (isset($dictionary[$item->getId()]))
			{
				$intersect->getId($item);
			}
		}

		return $intersect;
	}

	/**
	 * Return only unique items from the collection.
	 *
	 * @return BaseCollection
	 */
	public function unique()
	{
		$dictionary = $this->getDictionary($this);

		return new static(array_values($dictionary));
	}

	/**
	 * Get a dictionary keyed by primary keys.
	 *
	 * @param  BaseCollection  $collection
	 *
	 * @return array
	 */
	protected function getDictionary($collection)
	{
		$dictionary = array();

		foreach ($collection as $value)
		{
			$dictionary[$value->getId()] = $value;
		}

		return $dictionary;
	}

	/**
	 * Get a base Support collection instance from this collection.
	 *
	 * @return BaseCollection
	 */
	public function toBase()
	{
		return new BaseCollection($this->items);
	}

	/**
	 * Magic method which allows you to run a DataModel method to all items in the collection.
	 *
	 * For example, you can do $collection->save('foobar' => 1) to update the 'foobar' column to 1 across all items in
	 * the collection.
	 *
	 * IMPORTANT: The return value of the method call is not returned back to you!
	 *
	 * @param string $name       The method to call
	 * @param array  $arguments  The arguments to the method
	 */
	function __call($name, $arguments)
	{
		if (empty($this))
		{
			return;
		}

		if (method_exists('Awf\Mvc\DataModel', $name))
		{
			foreach ($this as $item)
			{
				switch (count($arguments))
				{
					case 0:
						$item->$name();
						break;

					case 1:
						$item->$name($arguments[0]);
						break;

					case 2:
						$item->$name($arguments[0], $arguments[1]);
						break;

					case 3:
						$item->$name($arguments[0], $arguments[1], $arguments[2]);
						break;

					case 4:
						$item->$name($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
						break;

					case 5:
						$item->$name($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
						break;

					case 6:
						$item->$name($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4], $arguments[5]);
						break;

					default:
						call_user_func_array(array($item, $name), $arguments);
						break;
				}
			}
		}
	}
}