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
?>
