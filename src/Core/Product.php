<?php
declare(strict_types=1);

namespace ECD\Core;

/**
 * Base class for Product information.
 **/
class Product
{
	/** @var string $make */
	private string $make;
	/** @var string $model */
	private string $model;
	/** @var string|null $colour */
	private ?string $colour = null;
	/** @var string|null $capacity */
	private ?string $capacity = null;
	/** @var string|null $network */
	private ?string $network = null;
	/** @var string|null $grade */
	private ?string $grade = null;
	/** @var string|null $condition */
	private ?string $condition = null;

	/**
	 * @return string
	 * @throws \Exception if no value
	 */
	public function getMake() : string
	{
	    if ( empty($this->make) )	{
	        throw new \Exception("Make does not contain a value");
	    }
	    return $this->make;
	}

	/**
	 * @return string
	 * @throws \Exception if no value
	 */
	public function getModel() : string
	{
	    if ( empty($this->model) )	{
	        throw new \Exception("Model does not contain a value");
	    }
	    return $this->model;
	}

	/**
	 * @return string|null
	 */
	public function getColour()
	{
		return $this->colour;
	}

	/**
	 * @return string|null
	 */
	public function getCapacity()
	{
		return $this->capacity;
	}

	/**
	 * @return string|null
	 */
	public function getNetwork()
	{
		return $this->network;
	}

	/**
	 * @return string|null
	 */
	public function getGrade()
	{
		return $this->grade;
	}

	/**
	 * @return string|null
	 */
	public function getCondition()
	{
		return $this->condition;
	}

	/**
	 * @param string $make
	 * @throws \Exception if no value
	 */
	public function setMake(string $make)
	{
	    if ( empty($make) )	{
	        throw new \Exception("Make must contain a value");
	    }
	    $this->make = $make;
	}

	/**
	 * @param string $model
	 * @throws \Exception if no value
	 */
	public function setModel(string $model)
	{
	    if ( empty($model) )	{
	        throw new \Exception("Model must contain a value");
	    }
	    $this->model = $model;
	}

	/**
	 * @param string $colour
	 */
	public function setColour(string $colour)
	{
		$this->colour = $colour;
	}

	/**
	 * @param string $capacity
	 */
	public function setCapacity(string $capacity)
	{
		$this->capacity = $capacity;
	}

	/**
	 * @param string $network
	 */
	public function setNetwork(string $network)
	{
		$this->network = $network;
	}

	/**
	 * @param string $grade
	 */
	public function setGrade(string $grade)
	{
		$this->grade = $grade;
	}

	/**
	 * @param string $condition
	 */
	public function setCondition(string $condition)
	{
		$this->condition = $condition;
	}

	/**
	 * For a Product, either a missing make or model make this invalid.
	 * @return bool
	 */
    public function validate() : bool
    {
        if ( empty($this->make) || empty($this->model) )    {
            return false;
        }
        return true;
    }

	/**
	 * Generates a unique identifier for this product.
	 * This is used to group the same products together, so can be tuned
	 * to group items according to requirements.
	 * @return string
	 */
	public function getUniqueIdentifier() : string
	{
        return json_encode(get_object_vars($this));
	}

	/**
	 * Returns the product data as an associative array.
	 * @return string[]
	 * @throws \Exception if Product isn't valid
	 */
	public function getDataAsArray() : array
	{
	    if ( $this->validate() === false )	{
	        throw new \Exception("Invalid product data.");
	    }
	    return [ "make" => $this->getMake(),
	        "model" => $this->getModel(),
	        "colour" => $this->getColour(),
	        "capacity" => $this->getCapacity(),
	        "network" => $this->getNetwork(),
	        "grade" => $this->getGrade(),
	        "condition" => $this->getCondition()
	    ];
	}
}