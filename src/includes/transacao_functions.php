<?
    include("auth_functions.php");
/*       
	function create_transaction($row){
        date_default_timezone_set('America/Sao_Paulo');
        $criacao = date('r');
		$result=mysql_query("INSERT INTO `transacao` (`idexterno`, `valor`, `codigostatus`, `criacao`) VALUES ('$row[1]', '$row[2]', '$row[3]', '$criacao');");
	}
*/
	function get_all_transactions(){
		$result=mysql_query("SELECT `id`, `idexterno`, `valor`, `nome`  FROM `transacao`, `status` WHERE `codigostatus` = `codigo` ORDER BY `id` DESC");
        $rows = array();
        while (	$row=mysql_fetch_row($result) ){
            array_push($rows, $row);
        }
		return $rows;
	}
    
	function get_all_status(){
        $result=mysql_query("SELECT * FROM `status` ORDER BY `nome`");
        $rows = array();
        while (	$row=mysql_fetch_row($result) ){
            array_push($rows, $row);
        }
		return $rows;
	}
    
	function update_transaction($row){
		$result=mysql_query("UPDATE `transacao` SET `codigostatus` = '$row[3]', `modificacao`=now() WHERE `id` = '$row[0]';");
	}
?>
