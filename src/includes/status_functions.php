<?
    include("auth_functions.php");
    
	function create_status($row){
		$result=mysql_query("INSERT INTO `status` (`nome`, `descricao`) VALUES ('$row[1]', '$row[2]');");
	}

	function read_status(){
		$result=mysql_query("SELECT * FROM `status` ORDER BY `nome`");
        $rows = array();
        while (	$row=mysql_fetch_row($result) ){
            array_push($rows, $row);
        }
		return $rows;
	}
    
	function update_status($row){
		$result=mysql_query("UPDATE `status` SET `codigo` = '$row[0]', `nome` = '$row[1]', `descricao` = '$row[2]' WHERE `codigo` = '$row[0]';");
	}
    
	function delete_status($pid){
		$result=mysql_query("DELETE FROM `status` WHERE `codigo`='$pid'");
	}
?>
