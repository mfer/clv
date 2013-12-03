<?
    function auth($email, $senha){
        $senha = md5($senha);
		
        $query = sprintf("SELECT `nome`,`sobrenome` FROM `administrador` WHERE `email` = '%s' AND `senha` = '%s'",
            mysql_real_escape_string($email),
            mysql_real_escape_string($senha));

		$result=mysql_query($query);
        
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        if (mysql_num_rows($result) == 1) {        
            $status = 'dentro'; 
            $msg_color ='#0F0';
            $msg = "Login efetuado com sucesso!";
        } else {
            $status = 'fora'; 
            $msg_color ='#F00';
            $msg = "Email ou senha estão incorretos.";
        }
        
        mysql_free_result($result);	
        
        return array ( $status, $msg, $msg_color );
    }
    
	function create_product($row){
		$result=mysql_query("INSERT INTO `produto` (`nome`, `descricao`, `preco`, `imagem`, `quantidadeestoque`) VALUES ('$row[1]', '$row[2]', '$row[3]', '$row[4]', '$row[5]');");
	}

	function get_all_products(){
		$result=mysql_query("SELECT * FROM `produto` ORDER BY `nome`");
        $rows = array();
        while (	$row=mysql_fetch_row($result) ){
            array_push($rows, $row);
        }
		return $rows;
	}
    
	function remove_product($pid){
		$result=mysql_query("DELETE FROM `produto` WHERE `sku`='$pid'");
	}
    
	function update_product($row){
		$result=mysql_query("UPDATE `produto` SET `sku` = '$row[0]', `nome` = '$row[1]', `descricao` = '$row[2]', `preco` = '$row[3]', `imagem` = '$row[4]', `quantidadeestoque`='$row[5]'  WHERE `sku` = '$row[0]';");
	}
    
	function set_image_product($row){
		$result=mysql_query("UPDATE `produto` SET `sku` = '$row[0]', `imagem` = '$row[4]' WHERE `sku` = '$row[0]';");
	}
    
	function get_name_product($pid){
		$result=mysql_query("SELECT `nome` FROM `produto` WHERE `sku`='$pid'");
        $row=mysql_fetch_row($result);
        return $row['0'];
	}
	function get_image_product($pid){
		$result=mysql_query("SELECT `imagem` FROM `produto` WHERE `sku`='$pid'");
        $row=mysql_fetch_row($result);
        return $row['0'];
	}
?>
