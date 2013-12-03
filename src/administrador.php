<?
	include("includes/db.php");
	include("includes/auth_functions.php");
    
	if($_REQUEST['command']=='login'){
		
		$email=$_REQUEST['email'];
        $senha=$_REQUEST['senha'];
        list ( $_SESSION['status'], $msg, $msg_color ) = auth($email, $senha);
        
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
<title>Administrador</title>
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
