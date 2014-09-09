<?php
class DB{
	private static $_instance = null;
	private $_pdo, 
	$_query, 
	$_error=false, 
	$_results, 
	$_count=0;

	private function __construct(){
		try{
			$this->_pdo = new PDO('mysql:host=' . config::get('mysql/host') . ';dbname=' . config::get('mysql/db'), config::get('mysql/username'), config::get('mysql/password'));
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}

	// this creates a new instance of the DB class
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	// this is used to run a query
	public function query($sql, $params = array()){
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)){
			$x = 1;
			if(count($params)){
				foreach($params as $param){
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}

			if($this->_query->execute()){
				$this->_results = $this->_query->fetchALL(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			}else{
				$this->_error = true;
			}
		}

		return $this;
	}

	// the action function is used to make querys
	// but we do this in a sepcial way, by using variables as the operators, tables, and actions(ie: SELECT, DELETE, INSERT)
	public function action($action, $table, $where = array()){

		// this only runs if all 3 variables are inputed into the array
		if(count($where) === 3){
			// the operators is actually an array of string operators
			$operators = array('=', '>', '<', '>=', '<=');

			// the first variable in the where array is the field (ie: WHERE users) users is the field
			$field    = $where[0];
			
			// the second variable in the where array is the operator
			// we set our operator to 0, in this case so we get equals
			$operator = $where[1];

			// the third variable in the where array is the value(ie: Simple, or the username you want to find)
			$value    = $where[2];

			// this runs if the operator, and the oprators variables are set
			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				// we're calling the query function
				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}
		return false;
	}

	// this gets(returns) the information of the action() function
	public function getAction($table, $where){
		return $this->action('SELECT *', $table, $where);
	}

	// this deletes tables using the action() function
	public function deleteAction($table, $where){
		return $this->action('DELETE', $table, $where);
	}

	// this returns the rusults variable
	public function results(){
		return $this->_results;
	}

	public function insert($string, $arr = array()){
		// string = table, arr = fields
		if(count($arr)){
			$kys = array_keys($arr);
			$val = '';
			$cnt = 1;

			foreach($arr as $ar) {
				$val .= '?';
				if($cnt<count($arr)){
					$val .= ', ';
				}
				$cnt++;
			}

			$sql = "INSERT INTO users (`". implode('`, `', $kys) . "`) VALUES ({$val})";
			
			if(!$this->query($sql, $arr)->error()) {
				return true;
			}
		}
		return false;
    }

    public function update($string, $id, $fields){
    	$set = '';
    	$x = 1;

    	foreach ($fields as $name => $value) {
    		$set .= "{$name} = ?";
    		if($x < count($fields)) {
    			$set .= ', ';
    		}
    		$x++;
    	}
    	$sql = "UPDATE {$string} SET {$set} WHERE id = {$id}";
    	
    	if(!$this->query($sql, $fields)->error()){
    		return true;
    	}
    	return false;
    }

	// returns the first value of results
	public function first() {
		return $this->results()[0];
	}

	// this returns the error variable
	public function error(){
		return $this->_error;
	}

	// this returns the count variable
	public function count() {
		return $this->_count;
	}
}
?>