<?php


class DB {
	private $driver;
	
  	public function __construct()
    {
        //create db connection
        $this->driver = new PDO('pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';', DB_USER, DB_PASSWORD);
  		//$this->driver = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
        //set character set
  		$this->driver->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
  	}

    /*
     * The $param is to pass in an array of parameters to bind to the sql statement
     * $params = array([value1], [value2])
     *
     */
    public function queryPreparedStatement($sql, $params = array())
    {
        //prepare statement
        $conn = $this->driver;
        $stmt = $conn->prepare($sql);

        //execute statement
        $stmt->execute($params);

        //return the data
        return $stmt->fetchAll();
    }
    
    public function getRowLimitSql($number_of_rows, $page_number)
    {
        //calculate start row
        $start_row = $number_of_rows * ($page_number - 1);

        //build sql
        $sql = ' LIMIT ' . $start_row . ', ' . $number_of_rows;
        
        //return 
        return $sql;
    }     
	
  	public function escape($value, $addQuote = true) 
    {
  		//set return string based on if we are to add quotes or not
		if( $addQuote )
		{	
			$return = "'" . $value . "'";
		} 
		else 
		{
			$return = $value;
		}
		
		return $return;
  	}

  	public function getLastId() 
    {
    	return $this->driver->lastInsertId();
  	}	
    
    public function getDriver() 
    {
        return $this->driver;
    }
	
  	public function __destruct() 
    {
  		$this->driver = null;
  	}

}
?>
