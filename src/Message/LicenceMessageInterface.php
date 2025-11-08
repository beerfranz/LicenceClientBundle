<?php

namespace Beerfranz\LicenceClientBundle\Message;

interface LicenceMessageInterface
{
	public function setId($id): self;
	public function getId();
	public function __set(string $key, mixed $value);
}
