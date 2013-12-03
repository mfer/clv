<?
	include("includes/db.php");
	include("includes/status_functions.php");
    
	if($_REQUEST['command']=='login'){
		
		$email=$_REQUEST['email'];
        $senha=$_REQUEST['senha'];
        list ( $_SESSION['status'], $msg, $msg_color ) = auth($email, $senha);
        
	}else if($_REQUEST['command']=='create'){
        $status=array();
        $status[1]=$_REQUEST['sname_c'];
        $status[2]=$_REQUEST['sdesc_c'];
        create_status($status);
        
	}else if($_REQUEST['command']=='edit'){
        $pedit = $_REQUEST['pid'];

	}else if($_REQUEST['command']=='update'){
        $status=array();
        $status[0]=$_REQUEST['pid'];
        $status[1]=$_REQUEST['sname'];
        $status[2]=$_REQUEST['sdesc'];
        update_status($status);
        
	}else if($_REQUEST['command']=='delete'){
        remove_status($_REQUEST['pid']);
      
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
<title>Status</title>
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
	function create(){
		var f=document.form1;
		if(f.sname_c.value==''){
			alert('O nome é obrigatório.');
			f.sname_c.focus();
			return false;
		}
		if(f.sdesc_c.value==''){
			alert('A descrição é obrigatória.');
			f.sdesc_c.focus();
			return false;
		}
		f.command.value='create';
		f.submit();
	}
	function edit(pid){
		document.form1.pid.value=pid;
		document.form1.command.value='edit';
		document.form1.submit();
	}
	function update(pid){
		var f=document.form1;
        f.pid.value=pid;
		if(f.sname.value==''){
			alert('O nome é obrigatório.');
			f.sname.focus();
			return false;
		}
		if(f.sdesc.value==''){
			alert('A descrição é obrigatória.');
			f.sdesc.focus();
			return false;
		}
		f.command.value='update';
		f.submit();
	}
	function del(pid){
		if(confirm('Você realmente quer remover esse item?')){
			document.form1.pid.value=pid;
			document.form1.command.value='delete';
			document.form1.submit();
		}
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
<input type="hidden" name="pid" />
	<div style="margin:0px auto; width:600px;" >
    <div style="padding-bottom:10px">
    	<h1 align="center">Status</h1>
        <div style="color:<?=$msg_color?>"><?=$msg?></div>
    </div>        
    	<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
    	<?
            	echo '<tr bgcolor="#FFFFFF" style="font-weight:bold">
                    <td>#</td>
                    <td>Nome</td>
                    <td>Descrição</td>
                    <td>Opções</td></tr>';
                    
            $statuss = read_status();
            
			if( count($statuss) > 0 ){
                    
				foreach ($statuss as $status){
					$pid   =$status[0];
					$sname =$status[1];
					$sdesc =$status[2];
                    
                    if ( $pid == $pedit) {
			?>
            		<tr bgcolor="#FFFFFF">
                        <td><?=$pid?></td>
                        <td><input type="text" name="sname" value="<?=$sname?>"/></td>
                        <td><input type="text" name="sdesc" value="<?=$sdesc?>"/></td>
                        <td><a href="javascript:del(<?=$pid?>)">Remover</a>
                            <a href="javascript:update(<?=$pid?>)">Atualizar</a></td>
                    </tr>
            <?      } else { ?>
            		<tr bgcolor="#FFFFFF">
                        <td><?=$pid?></td>
                        <td><?=$sname?></td>
                        <td><?=$sdesc?></td>
                        <td><a href="javascript:edit(<?=$pid?>)">Editar</a></td>
                    </tr>
            <?      }
				}
            }
			else{
				echo '<tr bgColor=\'#FFFFFF\'><td colspan="6" align="left">Sua Lista de Status está vazia!</td>';
			}
			?>
				<tr>
                        <td>+</td>
                        <td><input type="text" name="sname_c" /></td>
                        <td><input type="text" name="sdesc_c" /></td>
                        <td><a href="javascript:create()">Adicionar</a></td>
                </tr>
			<?
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
