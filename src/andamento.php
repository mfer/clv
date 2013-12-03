<?
	include("includes/db.php");
	include("includes/andamento_functions.php");
    
    if($_REQUEST['command']=='read'){
		$transaction = get_transaction($_REQUEST['tidbcash']);
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Andamento</title>
</head>

<body>

<form name="form1" method="post">
<input type="hidden" name="command" />
<input type="hidden" name="tid" />
	<div style="margin:0px auto; width:600px;" >
    <div style="padding-bottom:10px">
    	<h1 align="center">Andamento</h1>
        <div style="color:<?=$msg_color?>"><?=$msg?></div>
    </div>        
    	<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
            <tr style="font-weight:bold"><td>Chave</td> <td>Valor</td></tr>
    	<?
            if(count($transaction) > 0){
                foreach($transaction as $key => $value){
                    if (is_array($value)){
                        foreach($value as $k => $v){
                            echo '<tr bgcolor="#FFFFFF"><td style="font-weight:bold">'.$k.'</td><td>'.$v.'</td></tr>';
                        }                        
                    }else{                        
                        echo '<tr bgcolor="#FFFFFF"><td style="font-weight:bold">'.$key.'</td><td>'.$value.'</td></tr>';
                    }
                }
            }
			else{
				echo '<tr bgColor=\'#FFFFFF\'><td colspan="6" align="left">Erro ao acessar transação... Retorne a esse endereço mais tarde.</td></tr>';
			}
		?>
        </table>
    </div>
</form>


</body>
</html>
