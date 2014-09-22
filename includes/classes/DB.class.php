<?php
class DB {
    //open a connection to the database. Make sure this is called
    //on every page that needs to use the database.
    public function connect($CONF) {
        $this->connection = mysql_connect($CONF['db_host'], $CONF['db_user'], $CONF['db_pass']);
        mysql_select_db($CONF['db_name']);
 
        return true;
    }
 
    //takes a mysql row set and returns an associative array, where the keys
    //in the array are the column names in the row set. If singleRow is set to
    //true, then it will return a single row instead of an array of rows.
    public function processRowSet($rowSet, $singleRow=false)
    {
        $resultArray = array();
        while ($row = mysql_fetch_assoc($rowSet))
        {
            array_push($resultArray, $row);
        }
 
        if($singleRow === true)
            return $resultArray[0];
 
        return $resultArray;
    }
 
    //Select rows from the database.
    //returns a full row or rows from $table using $where as the where clause.
    //return value is an associative array with column names as keys.
    public function select($table, $where, $where_data, $fields = "*") { 
        //any amendments?
        $this->_query("select $fields FROM $table WHERE $where", $where_data);
        
        if (mysql_num_rows($this->dbresult) == 1)
            return $this->processRowSet($this->dbresult, true);
            
        return $this->processRowSet($this->dbresult);
    }
 
    //Select rows from the database.
    //returns a full row or rows from $table using $where as the where clause.
    //return value is an associative array with column names as keys.
    public function select_raw($table, $args, $args_data, $fields = "*") { 
        //any amendments?
        $this->_query("select $fields FROM $table $args", $args_data);
        
        if (mysql_num_rows($this->dbresult) == 1)
            return $this->processRowSet($this->dbresult, true);
            
        return $this->processRowSet($this->dbresult);
    }
 
    //Updates a current row in the database.
    //takes an array of data, where the keys in the array are the column names
    //and the values are the data that will be inserted into those columns.
    //$table is the name of the table and $where is the sql where clause.
    public function update($data, $table, $where, $where_data) {
        foreach ($data as $column => $value) {
            $this->_query("UPDATE $table SET $column=? WHERE $where", $value, $where_data);
        }
        return true;
    }
 
    //Deletes row(s) in the database.
    //takes an array of data, where the keys in the array are the column names
    //and the values are the data that will be inserted into those columns.
    //$table is the name of the table and $where is the sql where clause.
    public function delete($table, $where, $where_data, $fields = "") {
        $this->_query("delete $fields FROM $table WHERE $where", $where_data);
        return true;
    }
 
    //Inserts a new row into the database.
    //takes an array of data, where the keys in the array are the column names
    //and the values are the data that will be inserted into those columns.
    //$table is the name of the table.
    public function insert($data, $table) {
 
        $columns = "";
        $placeholders = "";
        $values = array();
 
        foreach ($data as $column => $value) {
            $columns .= ($columns == "") ? "" : ", ";
            $columns .= $column;
            $placeholders .= ($placeholders == "") ? "" : ", ";
            $placeholders .= "?";
            array_push($values, $value);
        }
 
        $this->_query("insert into $table ($columns) values ($placeholders)", $values);
 
        //return the ID of the user in the database.
        return $this->_get_insert_id();
 
    }
    
    // How many pastes are in the database?
    function getPasteCount()
    {
    	$this->_query('select count(*) as cnt from paste');
    	return $this->_next_record() ? $this->_f('cnt') : 0;
    }
    
    // Delete oldest $deletecount pastes from the database.
    function trimPastes($deletecount)
    {
    	// Build a one-shot statement to delete old pastes
      $sql='delete from paste where pid in (';
      $sep='';
      $this->_query("select * from paste order by posted asc limit $deletecount");
      while ($this->_next_record())
      {
        $sql.=$sep.$this->_f('pid');
        $sep=',';
      }
      $sql.=')';
      
      // Delete extra pastes.
      $this->_query($sql);	
    }
    
    // Delete all expired pastes.
    function deleteExpiredPastes()
    {
    	$this->_query("delete from paste where expires is not null and now() > expires");	
    }
    
    // Add paste and return ID.
    function addPost($title,$format,$code,$parent_pid,$expiry_flag,$password,$user_id)
    {
    	//figure out expiry time
    	switch ($expiry_flag)
    	{
    		case 'd':
    			$expires="DATE_ADD(NOW(), INTERVAL 1 DAY)";
    			break;
			case 'f':
				$expires="NULL";
				break;
			default:
    			$expires="DATE_ADD(NOW(), INTERVAL 1 MONTH)";
    			break;	
    	}
    	$this->_query('insert into paste (title, posted, format, code, parent_pid, expires, expiry_flag, password, user_id) '.
				"values (?, now(), ?, ?, ?, $expires, ?, ?, ?)",
				$title,$format,$code,$parent_pid,$expiry_flag,$password,$user_id);	
      $id=$this->_get_insert_id();	
      return $id;
    }
    
    // Return entire paste row for given ID.
    function getPaste($id)
    {
      $this->_query('select *,date_format(posted, \'%M %a %D %l:%i %p\') as postdate '.'from paste where pid=?', $id);
    	if ($this->_next_record())
    		return $this->row;
    	else
    		return false;
		
    }
    
    // Return summaries for $count posts ($count=0 means all)
    function getRecentPostSummary($count)
    {
    	$limit=$count?"limit $count":"";
    	
    	$posts=array();
    	$this->_query("select pid,title,unix_timestamp()-unix_timestamp(posted) as age, ".
			"date_format(posted, '%a %D %b %H:%i') as postdate ".
			"from paste ".
			"order by posted desc, pid desc $limit");
      while ($this->_next_record())
      {
        $posts[]=$this->row;	
      }
      
      return $posts;
    }
    
    // Get follow up posts for a particular post
    function getFollowupPosts($pid, $limit=5)
    {
    	//any amendments?
      $childposts=array();
      $this->_query("select pid,title,".
        "date_format(posted, '%a %D %b %H:%i') as postfmt ".
        "from paste where parent_pid=? ".
        "order by posted limit $limit", $pid);
      while ($this->_next_record())
      {
        $childposts[]=$this->row;
      }
      return $childposts;	
    }

    // Save formatted code for displaying.
    function saveFormatting($pid, $codefmt, $codecss)
    {
    	$this->_query("update paste set codefmt=?,codecss=? where pid=?",
    		$codefmt, $codecss, $pid);
    }
     
	// Execute query - should be regarded as private to insulate the rest ofthe application from sql differences.
	function _query($sql)
	{
		// Been passed more parameters? do some smart replacement.
		if (func_num_args() > 1)
		{
			// Query contains ? placeholders, but it's possible the
			// replacement string have ? in too, so we replace them in
			// our sql with something more unique
			$q=md5(uniqid(rand(), true));
			$sql=str_replace('?', $q, $sql);
			
			$args=func_get_args();
			for ($i=1; $i<=count($args); $i++)
			{
        if(isset($args[$i])){
          if(is_array($args[$i]))
          {
            for ($x=0; $x<=count($args[$i]); $x++)
            {
              $sql=preg_replace("/$q/", "'".preg_quote(mysql_real_escape_string($args[$i][$x]))."'", $sql,1);
            }
          }
          else
          {
            $sql=preg_replace("/$q/", "'".preg_quote(mysql_real_escape_string($args[$i]))."'", $sql,1);
          }
        }
				
			}
			// We shouldn't have any $q left, but it will help debugging if we change them back!
			$sql=str_replace($q, '?', $sql);
		}
		
		$this->dbresult=mysql_query($sql, $this->connection);
		if (!$this->dbresult)
		{
			die("Query failure: ".mysql_error()."<br />$sql");
		}
		return $this->dbresult;
	}
	
	// get next record after executing _query.
	function _next_record()
	{
		$this->row=mysql_fetch_array($this->dbresult);
		return $this->row!=FALSE;
	}
	
	// Get result column $field.
	function _f($field)
    {
    	return $this->row[$field];
    }
 
	// Get the last insertion ID.
	function _get_insert_id()
	{
		return mysql_insert_id($this->connection);
	}
	
	// Get last error.
	function get_db_error()
	{
		return mysql_last_error();
  }
}
?>