<?
	include("includes/db.php");
	include("includes/carrinho_functions.php");

    function frete(){
        $_SESSION['sCepDestino']=$_REQUEST['sCepDestino'];
        $_SESSION['nCdServico']=$_REQUEST['nCdServico'];
		if (get_frete()=='') $msg="O frete não pode ser calculado.";
    }
    
	if($_REQUEST['command']=='delete' && $_REQUEST['pid']>0){
		del_cart($_REQUEST['pid']);
	} else if($_REQUEST['command']=='clear'){
		unset($_SESSION['cart']);
	} else if($_REQUEST['command']=='update'){
        update_cart();
	} else if($_REQUEST['command']=='frete'){
        frete();
	} else if($_REQUEST['command']=='checkout'){
        update_cart();
        frete();
        header("location:pagamento.php");
        exit();
	}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Carrinho</title>
<script language="javascript">
	function del(pid){
		if(confirm('Você realmente quer remover esse item?')){
			document.form1.pid.value=pid;
			document.form1.command.value='delete';
			document.form1.submit();
		}
	}
	function clear_cart(){
		if(confirm('Esvaziando carrinho, confirma?')){
			document.form1.command.value='clear';
			document.form1.submit();
		}
	}
	function update_cart(){
		document.form1.command.value='update';
		document.form1.submit();
	}

    function updateTextInput(val, pid) {
        document.getElementById('product'.concat(pid)).value=val; 
    }

    function verifica_correio(f){
		if(f.sCepDestino.value==''){
			alert('O CEP é obrigatório.');
			f.sCepDestino.focus();
			return false;
		}
		if(f.nCdServico.value=='00000'){
			alert('O serviço é obrigatório.');
			f.nCdServico.focus();
			return false;
		}
        return true;
    }
	function get_frete(f){
		var f=document.form1;
        verifica_correio(f);
		f.command.value='frete';
		f.submit();
	}
	function checkout_cart(){
		var f=document.form1;
        if (!verifica_correio(f)){
            return false;
        }
        f.command.value ='checkout';
		f.submit();
	}

</script>
</head>

<body>

<div align="right">
    <a style="text-decoration: none" href="catalogo.php">Catálogo</a>
    <a style="text-decoration: none" href="carrinho.php">Carrinho</a>
    <a style="text-decoration: none" href="pagamento.php">Pagamento</a>
</div>

<form name="form1" method="post">
<input type="hidden" name="pid" />
<input type="hidden" name="command" />
<input type="hidden" name="frete" />
	<div style="margin:0px auto; width:700px;" >
    <div style="padding-bottom:10px">
    	<h1 align="center">Carrinho</h1>    
    <a style="text-decoration: none" href="catalogo.php"><input type="button" value="Continuar comprando"/></a>
    </div>
    	<div style="color:#F00"><?=$msg?></div>
    	<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
    	<?
			if(is_array($_SESSION['cart'])){
            	echo '<tr bgcolor="#FFFFFF" style="font-weight:bold"><td>#</td><td>Nome</td><td>Preço</td><td>Quantidade</td><td>SubTotal</td><td>Opções</td></tr>';
				$max=count($_SESSION['cart']);
                
				for($i=0;$i<$max;$i++){
					$pid=$_SESSION['cart'][$i]['productid'];
					$q=$_SESSION['cart'][$i]['qty'];
					$pname=get_product_name($pid);
                    $pstock=get_product_stock($pid);

					if($q==0) continue;
			?>
            		<tr bgcolor="#FFFFFF">
                        <td><?=$i+1?></td>
                        <td><?=$pname?></td>
                        <td>R$ <?=number_format(get_price($pid), 2, '.', ' ')?></td>
                        <td>
                            <input type="range" name="rangeInput<?=$pid?>" value="<?=$q?>" min="1" max="<?=$pstock?>" onchange="updateTextInput(this.value, <?=$pid?>);">
                            <input type="text" id="product<?=$pid?>" name="product<?=$pid?>" onblur="update_cart()" value="<?=no_more_than($q,$pstock)?>" maxlength="3" size="2" />
                        </td>
                        <td>R$ <?=number_format(get_price($pid)*no_more_than($q,$pstock), 2, '.', ' ')?></td>
                    <td><a href="javascript:del(<?=$pid?>)">Remover</a></td></tr>
            <?					
				}                
			?>
            		<tr>
                        <td></td>
                        <td>Frete</td>
                        <td>
                            CEP: <input type="text" name="sCepDestino" maxlength="8"  size="10" value="<?=$_SESSION['sCepDestino']?>"/>
                        </td>
                        <td>
                            Serviço: 
                            <select name="nCdServico">
                                <option value="00000" <?if ('00000' == $_SESSION['nCdServico']) echo ' selected="selected"';?>></option>
                                <option value="41106" <?if ('41106' == $_SESSION['nCdServico']) echo ' selected="selected"';?>>PAC</option>
                                <option value="40010" <?if ('40010' == $_SESSION['nCdServico']) echo ' selected="selected"';?>>SEDEX</option>
                                <option value="40215" <?if ('40215' == $_SESSION['nCdServico']) echo ' selected="selected"';?>>SEDEX 10</option>
                                <option value="40290" <?if ('40290' == $_SESSION['nCdServico']) echo ' selected="selected"';?>>SEDEX HOJE</option>
                            </select>
                        </td>
                        <td>R$ <?=number_format(get_frete(), 2, '.', ' ')?></td>
                    <td><a href="javascript:get_frete()">Calcular</a></td></tr>
            
				<tr><td colspan="2"><b>Total: R$ <?=number_format(get_order_total(), 2, '.', ' ')?></b></td><td colspan="5" align="right">
                    <input type="button" value="Esvaziar" onclick="clear_cart()">
                    <input type="button" value="Atualizar" onclick="update_cart()">
                    <input type="button" value="Comprar" onclick="checkout_cart()"></td></tr>
			<?
            }
			else{
				echo "<tr bgColor='#FFFFFF'><td>Seu carrinho está vazio!</td>";
			}
		?>
        </table>
    </div>
</form>
</body>
</html>
