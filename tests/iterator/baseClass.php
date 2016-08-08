<?php

class Line_Iterator implements Iterator
{
	protected $myfile;
	
	public function __construct ($filepath)
	{
		$myfile = fopen($filepath, "r");
		$this->resource = $myfile;
	}
	
	public function current()
    {
		$this->myfile = fgets($this->resource);
		return $this->myfile;
    }
	

    public function next()
    {

    }

    public function key()
    {
        return $this->file;
    }

    public function valid()
    {
		return (!feof($this->resource));
    }

    public function rewind()
    {
        rewind($this->resource);
    }

    public function reverse()
    {
		
    }
}


$x = new Line_Iterator(__FILE__);
#var_dump($x);
$i = 0;
foreach ($x as $line){
	echo $line;
	$i++;
	if ($i == 11){
		exit;
	}
}

//class Random10 implements Iterator {
//	
//	protected $i = 0;
//	protected $min;
//	protected $max;
//	
//	public function __construct($min, $max) {
//		$this->min = $min;
//		$this->max = $max;
//	}
//	
//	public function current()
//    {
//        return rand($this->min, $this->max);
//    }
//	
//
//    public function next()
//    {
//        $this->i++;
//    }
//
//    public function key()
//    {
//        return $this->i;
//    }
//
//    public function valid()
//    {
//        return $this->i < 10;
//    }
//
//    public function rewind()
//    {
//        $this->i = 0;
//    }
//
//    public function reverse()
//    {
//        //
//    }
//}
//
//$random_list = new Random10(1,100);
//foreach($random_list as $key => $random_number) {
//	echo $key.":".$random_number . "\n";
//}