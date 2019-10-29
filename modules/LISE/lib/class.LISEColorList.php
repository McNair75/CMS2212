<?php

class LISEColorList
{
	#---------------------
	# Attributes
	#---------------------

	public $color_id = null;
	public $alias = null;	
	public $name = '';
	public $description = '';
        public $icon = '';
        public $picture = '';
	public $active = 1;
	public $position = 1;
	public $parent_id = -1;
	public $hierarchy = '';
	public $id_hierarchy = '';
	public $hierarchy_path = '';
	public $create_date = '';
	public $modified_date = '';
	
	public $key1 = null;
	public $key2 = null;
	public $key3 = null;
	
	public $items;
	public $children;
	static public $mandatory = array('name');
	
	#---------------------
	# Magic methods
	#---------------------		
	
	public function __construct() 
	{
		$this->items = array();
		$this->children = array();
	}
	
	public function __toString()
	{
		return (string)$this->name;
	}
	
} // end of class