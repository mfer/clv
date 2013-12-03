<?
	include("includes/db.php");
	include("includes/produto_functions.php");
    
	if($_REQUEST['command']=='login'){
		
		$email=$_REQUEST['email'];
        $senha=$_REQUEST['senha'];
        list ( $_SESSION['status'], $msg, $msg_color ) = auth($email, $senha);
        
	}else if($_REQUEST['command']=='create'){
        
        $_UP['pasta'] = 'images/';
        $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
        $_UP['extensoes'] = array('jpg', 'png', 'gif');
        // Array com os tipos de erros de upload do PHP
        $_UP['erros'][0] = 'Não houve erro';
        $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
        $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
        $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
        $_UP['erros'][4] = 'Não foi feito o upload do arquivo';

        // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
        if ($_FILES['ppict_c']['error'] != 0) {
            die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['arquivo']['error']]);
            exit; // Para a execução do script
        }

        // Faz a verificação da extensão do arquivo
        $extensao = strtolower(end(explode('.', $_FILES['ppict_c']['name'])));
        if (array_search($extensao, $_UP['extensoes']) === false) {
            echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif";
        }
        // Faz a verificação do tamanho do arquivo
        else if ($_UP['tamanho'] < $_FILES['ppict_c']['size']) {
            echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
        }
        // O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
        else {
            $nome_final = $_FILES['ppict_c']['name'];
            // Depois verifica se é possível mover o arquivo para a pasta escolhida
            if (move_uploaded_file($_FILES['ppict_c']['tmp_name'], $_UP['pasta'] . $nome_final)) {
                // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                echo "Upload efetuado com sucesso!";
                echo '<br /><a href="' . $_UP['pasta'] . $nome_final . '">Clique aqui para acessar o arquivo</a>';

        $product=array();
        $product[1]=$_REQUEST['pname_c'];
        $product[2]=$_REQUEST['pdesc_c'];
        $product[3]=$_REQUEST['ppric_c'];
        $product[4]=$nome_final;
        $product[5]=$_REQUEST['pqest_c'];
        create_product($product);
                header("Location: produto.php");

            } else {
                // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                echo "Não foi possível enviar o arquivo, tente novamente";
            }        
        }
        

        
	}else if($_REQUEST['command']=='edit'){
        $pedit = $_REQUEST['pid'];

	}else if($_REQUEST['command']=='update'){
        
        $product=array();
        $product[0]=$_REQUEST['pid'];
        $product[1]=$_REQUEST['pname'];
        $product[2]=$_REQUEST['pdesc'];
        $product[3]=$_REQUEST['ppric'];
        $product[4]=$_REQUEST['ppict'];
        $product[5]=$_REQUEST['pqest'];
        update_product($product);
        
	}else if($_REQUEST['command']=='alter'){
        $upload=true;
        $pid = $_REQUEST['pid'];
        
	}else if($_REQUEST['command']=='upload'){
        $_UP['pasta'] = 'images/';
        $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
        $_UP['extensoes'] = array('jpg', 'png', 'gif');
        // Array com os tipos de erros de upload do PHP
        $_UP['erros'][0] = 'Não houve erro';
        $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
        $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
        $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
        $_UP['erros'][4] = 'Não foi feito o upload do arquivo';

        // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
        if ($_FILES['arquivo']['error'] != 0) {
            die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['arquivo']['error']]);
            exit; // Para a execução do script
        }

        // Faz a verificação da extensão do arquivo
        $extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
        if (array_search($extensao, $_UP['extensoes']) === false) {
            echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif";
        }
        // Faz a verificação do tamanho do arquivo
        else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
            echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
        }
        // O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
        else {
            $nome_final = $_FILES['arquivo']['name'];
            // Depois verifica se é possível mover o arquivo para a pasta escolhida
            if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
                // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                echo "Upload efetuado com sucesso!";
                echo '<br /><a href="' . $_UP['pasta'] . $nome_final . '">Clique aqui para acessar o arquivo</a>';
                
                $product=array();
                $product[0]=$_REQUEST['pid'];
                $product[4]=$nome_final;
                set_image_product($product);
                header("Location: produto.php");

            } else {
                // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                echo "Não foi possível enviar o arquivo, tente novamente";
            }        
        }

        
	}else if($_REQUEST['command']=='delete'){
        remove_product($_REQUEST['pid']);
      
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
<title>Produto</title>
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
		var f=document.form3;
		if(f.pname_c.value==''){
			alert('O nome é obrigatório.');
			f.pname_c.focus();
			return false;
		}
		if(f.pdesc_c.value==''){
			alert('A descrição é obrigatória.');
			f.pdesc_c.focus();
			return false;
		}
		if(f.ppric_c.value==''){
			alert('O preço é obrigatório.');
			f.ppric_c.focus();
			return false;
		}
		if(f.ppict_c.value==''){
			alert('A imagem é obrigatória.');
			f.ppict_c.focus();
			return false;
		}
		if(f.pqest_c.value==''){
			alert('A quantidade de estoque é obrigatória.');
			f.pqest_c.focus();
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
		if(f.pname.value==''){
			alert('O nome é obrigatório.');
			f.pname.focus();
			return false;
		}
		if(f.pdesc.value==''){
			alert('A descrição é obrigatória.');
			f.pdesc.focus();
			return false;
		}
		if(f.ppric.value==''){
			alert('O preço é obrigatório.');
			f.ppric.focus();
			return false;
		}
		if(f.ppict.value==''){
			alert('A imagem é obrigatória.');
			f.ppict.focus();
			return false;
		}
		if(f.pqest.value==''){
			alert('A quantidade de estoque é obrigatória.');
			f.pqest.focus();
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

    <div style="margin:0px auto; width:800px;" >
        <div style="padding-bottom:10px">
            <h1 align="center">Produto</h1>
            <div style="color:<?=$msg_color?>"><?=$msg?></div>
        </div>
        <table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
            
    <?if($upload){?>
        <tr bgcolor="#FFFFFF" style="font-weight:bold"><td>#</td><td>Nome</td><td colspan="4">Alterando a imagem<td>Opções</td></tr>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="command" value="upload"/>
            <input type="hidden" name="pid" value="<?=$pid?>"/>
            <tr>
                <td><?=$pid?></td>
                <td><?=get_name_product($pid)?></td>
                <td colspan="4">
                    <input type="file" name="arquivo"/>
                </td>
                <td><input type="submit" value="Enviar" /><a href="produto.php">Cancelar</a></td>
            </tr>
            <div align="center"><img src="images/<?=get_image_product($pid)?>"></div>            
        </form> 
        
    <?}else{?>
        <tr bgcolor="#FFFFFF" style="font-weight:bold"><td>#</td><td>Nome</td><td>Descrição</td><td>Preço</td><td>Imagem</td><td>QtdEstoque</td><td>Opções</td></tr>
        <form name="form1" method="post">
            <input type="hidden" name="command" />
            <input type="hidden" name="pid" />
            <? $products = get_all_products();

            if( count($products) > 0 ){
                foreach ($products as $product){
                    $pid   =$product[0];
                    $pname =$product[1];
                    $pdesc =$product[2];
                    $ppric =$product[3];
                    $ppict =$product[4];
                    $pqest =$product[5];

                    if ( $pid == $pedit ) {?>
                        <tr bgcolor="#FFFFFF">
                        <td><?=$pid?></td>
                        <td><input type="text" name="pname" value="<?=$pname?>"/></td>
                        <td><input type="text" name="pdesc" value="<?=$pdesc?>"/></td>
                        <td><input type="text" name="ppric" value="<?=$ppric?>"/></td>                    
                        <td>
                            <input type="hidden" name="ppict" value="<?=$ppict?>"/>
                            <a href="produto.php?command=alter&pid=<?=$pid?>" title="Alterar Imagem"><?=$ppict?></a>
                        </td>
                        <td><input type="text" name="pqest" value="<?=$pqest?>"/></td>
                        <td><a href="javascript:del(<?=$pid?>)">Remover</a>
                            <a href="javascript:update(<?=$pid?>)">Atualizar</a></td>
                        </tr>
                    <?} else { ?>
                        <tr bgcolor="#FFFFFF">
                        <td><?=$pid?></td>
                        <td><?=$pname?></td>
                        <td><?=$pdesc?></td>
                        <td><?=$ppric?></td>
                        <td><?=$ppict?></td>
                        <td><?=$pqest?></td>
                        <td><a href="javascript:edit(<?=$pid?>)">Editar</a></td>
                        </tr>
                    <?}
                }
            } else {
                echo '<tr bgColor=\'#FFFFFF\'><td colspan="6" align="left">Sua Lista de Produtos está vazia!</td>';
            }?>
        </form>

        <form name="form3" method="post" enctype="multipart/form-data">
            <input type="hidden" name="command"/>
            <input type="hidden" name="pid"/> 
            <tr>
            <td>+</td>
            <td><input type="text" name="pname_c" /></td>
            <td><input type="text" name="pdesc_c" /></td>
            <td><input type="text" name="ppric_c" /></td>
            <td><input type="file" name="ppict_c" /></td>
            <td><input type="text" name="pqest_c" /></td>
            <td><a href="javascript:create()">Adicionar</a></td>
            </tr>
        </form> 
        
    <?}?>
        </table>
    </div>
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
