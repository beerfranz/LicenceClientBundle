<?php

namespace Beerfranz\LicenceClientBundle\Message;

use Beerfranz\LicenceClientBundle\Message\LicenceMessageInterface;

abstract class LicenceMessageAbstract implements LicenceMessageInterface
{

	protected $id;

	public function __construct($id = 0, array $args = [])
	{
		$this->setId($id);

		foreach($args as $key => $value)
		{
			$this->__set($key, $value);
		}

		return $this;
	}

	public function setId($id): self
	{
		$this->id = $id;
		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	public function __set(string $name, mixed $value)
	{

		$methodName = 'set' . ucfirst($name);
		
		if (method_exists($this, $methodName)) {
			$this->$methodName($value);
			return $this;
		}

		if (property_exists($this, $name)) {
			$this->$name = $value;
			return $this;
		}

		throw new \Exception('The property ' . $name . ' not exists, or no method ' . $methodName());
	}

}
