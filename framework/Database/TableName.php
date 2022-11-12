<?php 

namespace Framework\Database;

#[Attribute]

class TableName 
{
	private string $name;

	public function __construct(string $name)
	{ 
		$this->name = $name;
	}

}
