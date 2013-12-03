<?
	include("includes/db.php");
	include("includes/transacao_functions.php");
    
	if($_REQUEST['command']=='login'){
		
		$email=$_REQUEST['email'];
        $senha=$_REQUEST['senha'];
        list ( $_SESSION['status'], $msg, $msg_color ) = auth($email, $senha);
        
	}else if($_REQUEST['command']=='update'){
        $transaction=array();
        $transaction[0]=$_REQUEST['tid'];
        $transaction[3]=$_REQUEST['tstat'];
        update_transaction($transaction);
        
	}else if($_REQUEST['command']=='edit'){
        $pedit = $_REQUEST['tid'];
        
	}else if($_REQUEST['command']=='logout'){
        
        $_SESSION['status'] = 'fora';
        $msg_color ='#0F0';
        $msg = "Logout feito com sucesso.";
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transação</title>
<script language="javascript">
	function validate(){
		var f=document.form1;
		if(f.email.value==''){
			alert('O email é obrigatório.');
			f.email.focus();
			return false;
		}
		if(f.senha.value==''){
			alert('A senha é obrigatória.');
			f.senha.focus();
			return false;
		}
		f.command.value='login';
		f.submit();
	}
	function update(tid){
		var f=document.form1;
        f.tid.value=tid;
		if(f.tstat.value==''){
			alert('O status é obrigatório.');
			f.tstat.focus();
			return false;
		}
		f.command.value='update';
		f.submit();
    }
	function edit(tid){
		document.form1.tid.value=tid;
		document.form1.command.value='edit';
		document.form1.submit();
	}
</script>
</head>

<body>

<?if($_SESSION['status'] == 'dentro'){?>
        <div align="right">
            <a href="produto.php"><input type="submit" value="Produto" /></td></a>
            <a href="transacao.php"><input type="submit" value="Transacao" /></td></a>
            <a href="status.php"><input type="submit" value="Status" /></td></a>
        </div>
        <form name="form2" method="POST">
            <input type="hidden" name="command" value="logout" />
            <div align="right">                
                <table border="0" cellpadding="2px">
                    <tr><td>&nbsp;</td><td><input type="submit" value="Sair" /></td></tr>
                </table>
            </div>
        </form>

<form name="form1" method="post">
<input type="hidden" name="command" />
<input type="hidden" name="tid" />
    <div style="margin:0px auto; width:800px;" >
    <div style="padding-bottom:10px">
    	<h1 align="center">Transação</h1>
        <div style="color:<?=$msg_color?>"><?=$msg?></div>
    </div>        
    	<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
    	<?
            	echo '<tr bgcolor="#FFFFFF" style="font-weight:bold">
                    <td>#</td>
                    <td># bcash</td>
                    <td>Valor</td>
                    <td>Status</td>
                    <td>Opções</td></tr>';
                    
            $transactions = get_all_transactions();
            $status = get_all_status();
            
			if( count($transactions) > 0 ){
                    
				foreach ($transactions as $transaction){
					$tid   =$transaction[0];
					$tidbcash =$transaction[1];
					$tvalor =$transaction[2];
                    $tstat =$transaction[3];
                    $tcria =$transaction[4];
                    $tmodi =$transaction[5];
                    
                    if ( $tid == $pedit) {
			?>
            		<tr bgcolor="#FFFFFF">
                        <td><?=$tid?></td>
                        <td><?=$tidbcash?></td>
                        <td><?=$tvalor?></td>
                        <td>
                            <select name="tstat"> 
                            <?
                                foreach($status as $stat){
                                    echo '<option VALUE="'.$stat[0].'"';
                                    if ($stat[1] == $tstat) echo ' selected="selected"';
                                    echo '>'.$stat[1].'</option>';
                                }
                            ?>
                            </select>
                        </td>
                        <td><a href="javascript:update(<?=$tid?>)">Atualizar</a></td>
                    </tr>
            <?      } else { ?>
            		<tr bgcolor="#FFFFFF">
                        <td><?=$tid?></td>
                        <td><a href="andamento.php?command=read&tidbcash=<?=$tidbcash?>" target="_blank""><?=$tidbcash?></a></td>
                        <td><?=number_format($tvalor, 2, '.', ' ')?></td>
                        <td><?=$tstat?></td>
                        <td><a href="javascript:edit(<?=$tid?>)">Editar</a></td>
                    </tr>
            <?      }
				}
            }
			else{
				echo '<tr bgColor=\'#FFFFFF\'><td colspan="6" align="left">Sua Lista de Produtos está vazia!</td>';
			}
		?>
        </table>
    </div>
</form>

<?}else{?>
        <form name="form1" onsubmit="return validate()" method="POST">
            <input type="hidden" name="command" />
            <div align="center">
                <h1 align="center">Login</h1>
                <div style="color:<?=$msg_color?>"><?=$msg?></div>
                <table border="0" cellpadding="2px">
                    <tr><td>Email:</td><td><input type="text" name="email" value="<?=$email?>" /></td></tr>
                    <tr><td>Senha:</td><td><input type="password" name="senha" /></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" value="Entrar" /></td></tr>
                </table>
            </div>
        </form>
<?}?>

</body>
</html>
