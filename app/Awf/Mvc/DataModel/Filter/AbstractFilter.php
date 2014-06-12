<?php
/**
 * @package		awf
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Awf\Mvc\DataModel\Filter;


use Awf\Database\Driver;

abstract class AbstractFilter
{
	protected $db = null;

	/**
	 * The column name of the table field
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * The column type of the table field
	 *
	 * @var string
	 */
	protected $type = '';

	/**
	 * The null value for this type
	 *
	 * @var  mixed
	 */
	public $null_value = null;

	/**
	 * Constructor
	 *
	 * @param   Driver   $db           The database object
	 * @param   object   $field        The field information as taken from the db
	 */
	public function __construct($db, $field)
	{
		$this->db = $db;

		$this->name = $field->name;
		$this->type = $field->type;
	}

	/**
	 * Is it a null or otherwise empty value?
	 *
	 * @param   mixed  $value  The value to test for emptiness
	 *
	 * @return  boolean
	 */
	public function isEmpty($value)
	{
		return ($value === $this->null_value) || empty($value);
	}

	/**
	 * Returns the default search method for a field. This always returns 'exact'
	 * and you are supposed to override it in specialised classes. The possible
	 * values are exact, partial, between and outside, unless something
	 * different is returned by getSearchMethods().
	 *
	 * @see  self::getSearchMethods()
	 *
	 * @return  string
	 */
	public function getDefaultSearchMethod()
	{
		return 'exact';
	}

	/**
	 * Return the search methods available for this field class,
	 *
	 * @return  array
	 */
	public function getSearchMethods()
	{
		$ignore = array('isEmpty', 'getField', 'getFieldType', '__construct', 'getDefaultSearchMethod', 'getSearchMethods');

		$class = new \ReflectionClass(__CLASS__);
		$methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

		$tmp = array();

		foreach ($methods as $method)
		{
			$tmp[] = $method->name;
		}

		$methods = $tmp;

		if ($methods = array_diff($methods, $ignore))
		{
			return $methods;
		}

		return array();
	}

	/**
	 * Perform an exact match (equality matching)
	 *
	 * @param   mixed  $value  The value to compare to
	 *
	 * @return  string  The SQL where clause for this search
	 */
	public function exact($value)
	{
		if ($this->isEmpty($value))
		{
			return '';
		}

		if (is_array($value))
		{
			$db    = $this->db;
			$value = array_map(array($db, 'quote'), $value);

			return '(' . $this->getFieldName() . ' IN (' . implode(',', $value) . '))';
		}
		else
		{
			return $this->search($value, '=');
		}
	}

	/**
	 * Perform a partial match (usually: search in string)
	 *
	 * @param   mixed  $value  The value to compare to
	 *
	 * @return  string  The SQL where clause for this search
	 */
	abstract public function partial($value);

	/**
	 * Perform a between limits match (usually: search for a value between
	 * two numbers or a date between two preset dates). When $include is true
	 * the condition tested is:
	 * $from <= VALUE <= $to
	 * When $include is false the condition tested is:
	 * $from < VALUE < $to
	 *
	 * @param   mixed    $from     The lowest value to compare to
	 * @param   mixed    $to       The higherst value to compare to
	 * @param   boolean  $include  Should we include the boundaries in the search?
	 *
	 * @return  string  The SQL where clause for this search
	 */
	abstract public function between($from, $to, $include = true);

	/**
	 * Perform an outside limits match (usually: search for a value outside an
	 * area or a date outside a preset period). When $include is true
	 * the condition tested is:
	 * (VALUE <= $from) || (VALUE >= $to)
	 * When $include is false the condition tested is:
	 * (VALUE < $from) || (VALUE > $to)
	 *
	 * @param   mixed    $from     The lowest value of the excluded range
	 * @param   mixed    $to       The highest value of the excluded range
	 * @param   boolean  $include  Should we include the boundaries in the search?
	 *
	 * @return  string  The SQL where clause for this search
	 */
	abstract public function outside($from, $to, $include = false);

	/**
	 * Perform an interval search (usually: a date interval check)
	 *
	 * @param   string               $from      The value to search
	 * @param   string|array|object  $interval  The interval
	 *
	 * @return  string  The SQL where clause for this search
	 */
	abstract public function interval($from, $interval);

	/**
	 * Return the SQL where clause for a search
	 *
	 * @param   mixed   $value     The value to search for
	 * @param   string  $operator  The operator to use
	 *
	 * @return  string  The SQL where clause for this search
	 */
	public function search($value, $operator = '=')
	{
		if ($this->isEmpty($value))
		{
			return '';
		}

		$prefix = '';

		if (substr($operator, 0, 1) == '!')
		{
			$prefix = 'NOT ';
			$operator = substr($operator, 1);
		}

		return $prefix . '(' . $this->getFieldName() . ' ' . $operator . ' ' . $this->db->quote($value) . ')';
	}

	/**
	 * Get the field name
	 *
	 * @return  string 	The field name
	 */
	public function getFieldName()
	{
		$name = $this->db->qn($this->name);

		return $name;
	}

	/**
	 * Creates a field Object based on the field column type
	 *
	 * @param   object  $field   The field informations
	 * @param   array   $config  The field configuration (like the db object to use)
	 *
	 * @return  AbstractFilter  The Filter object
	 *
	 * @throws  \InvalidArgumentException
	 */
	public static function getField($field, $config = array())
	{
		$type = $field->type;

		$classType = self::getFieldType($type);

		$className = '\\Awf\\Mvc\\DataModel\\Filter\\' . ucfirst($classType);

		if (($classType !== false) && class_exists($className, true))
		{
			if (!isset($config['dbo']))
			{
				throw new \InvalidArgumentException('Database object unspecified creating a ' . $className . ' filter');
			}

			$db = $config['dbo'];

			$field = new $className($db, $field);

			return $field;
		}

		return null;
	}

	/**
	 * Get the class name based on the field Type
	 *
	 * @param   string  $type  The type of the field
	 *
	 * @return  string  the class name suffix
	 */
	public static function getFieldType($type)
	{
		if (!empty($type))
		{
			list($type, ) = explode(' ', $type);
			list($type, ) = explode('(', $type);
		}

		switch ($type)
		{
			case 'varchar':
			case 'text':
			case 'smalltext':
			case 'longtext':
			case 'char':
			case 'mediumtext':
			case 'character varying':
			case 'nvarchar':
			case 'nchar':
				$type = 'Text';
				break;

			case 'date':
			case 'datetime':
			case 'time':
			case 'year':
			case 'timestamp':
			case 'timestamp without time zone':
			case 'timestamp with time zone':
				$type = 'Date';
				break;

			case 'tinyint':
			case 'smallint':
				$type = 'Boolean';
				break;

			default:
				$type = 'Number';
				break;
		}

		return $type;
	}
} 