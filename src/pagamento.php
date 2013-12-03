<?
	include("includes/db.php");
	include("includes/carrinho_functions.php");

    // Função que valida o CPF
    function validaCPF($cpf) {
        $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
        if (strlen($cpf) != 11 || $cpf == '00000000000' 
            || $cpf == '11111111111' || $cpf == '22222222222' 
            || $cpf == '33333333333' || $cpf == '44444444444' 
            || $cpf == '55555555555' || $cpf == '66666666666' 
            || $cpf == '77777777777' || $cpf == '88888888888' 
            || $cpf == '99999999999') {
            return false;
        } else {   
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }
    
    if($_REQUEST['command']=='verify') {
        $_SESSION['cpf_valido'] =   validaCPF($_POST['cpf']);        
        $_SESSION['email']      =   $_POST['email'];
        $_SESSION['name']       =   $_POST['name'].' '.$_POST['sname'];
        $_SESSION['cpf']        =   $_POST['cpf'];
        $_SESSION['tel']        =   $_POST['telefone'];
        
        if(!$_SESSION['cpf_valido']){
            $msg_color ='#F00';
            $msg = "O CPF digitado é incorreto.";
        }
    } else if($_REQUEST['command']=='clear') {
        $_SESSION['cpf_valido'] =   false;
        $_SESSION['email']      =   '';
        $_SESSION['name']       =   '';
        $_SESSION['cpf']        =   '';
        $_SESSION['tel']        =   '';
    }
    
   
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pagamento</title>
<style type="text/css">
:invalid { 
  border-color: #e88;
  -webkit-box-shadow: 0 0 5px rgba(255, 0, 0, .8);
  -moz-box-shadow: 0 0 5px rbba(255, 0, 0, .8);
  -o-box-shadow: 0 0 5px rbba(255, 0, 0, .8);
  -ms-box-shadow: 0 0 5px rbba(255, 0, 0, .8);
  box-shadow:0 0 5px rgba(255, 0, 0, .8);
}

:required {
  border-color: #88a;
  -webkit-box-shadow: 0 0 5px rgba(0, 0, 255, .5);
  -moz-box-shadow: 0 0 5px rgba(0, 0, 255, .5);
  -o-box-shadow: 0 0 5px rgba(0, 0, 255, .5);
  -ms-box-shadow: 0 0 5px rgba(0, 0, 255, .5);
  box-shadow: 0 0 5px rgba(0, 0, 255, .5);
}

form {
  width:300px;
  margin: 20px auto;
}

input {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  border:1px solid #ccc;
  font-size:20px;
  width:300px;
  min-height:30px;
  display:block;
  margin-bottom:15px;
  margin-top:5px;
  outline: none;

  -webkit-border-radius:5px;
  -moz-border-radius:5px;
  -o-border-radius:5px;
  -ms-border-radius:5px;
  border-radius:5px;
}

input[type=submit] {
  background:none;
  padding:10px;
}
</style>
</head>


<body>
<div align="right">
    <a style="text-decoration: none" href="catalogo.php">Catálogo</a>
    <a style="text-decoration: none" href="carrinho.php">Carrinho</a>
    <a style="text-decoration: none" href="pagamento.php">Pagamento</a>
</div>

<?if($_SESSION['cpf_valido']){?>

<form name="bcash" action="https://www.bcash.com.br/checkout/pay/" method="post">
    <input type="hidden" name="command" />
    <input name="redirect" type="hidden" value="true">
    <input name="email_loja" type="hidden" value="manaphys@gmail.com">
    <input name="url_retorno" type="hidden" value="<?=$_SERVER['SERVER_NAME']?>/ibd/retorno.php">
    <input name="url_aviso" type="hidden" value="<?=$_SERVER['SERVER_NAME']?>/ibd/aviso.php">
    <input name="id_pedido" type="hidden" value="<?=$_SESSION['chave']?>">
    <?  if (!empty($_SESSION['cart'])) 
        {
            $contador = 1;
            foreach($_SESSION['cart'] as $row) 
            {
                echo '
                    <input name="produto_codigo_'.$contador.'" type="hidden" value="'.$row['productid'].'"> 
                    <input name="produto_descricao_'.$contador.'" type="hidden" value="'.get_description($row['productid']).'">
                    <input name="produto_qtde_'.$contador.'" type="hidden" value="'.$row['qty'].'"> 
                    <input name="produto_valor_'.$contador.'" type="hidden" value="'.get_price($row['productid']).'" > 
                    <input name="produto_extra_'.$contador.'" type="hidden" value="extra" >
                ';
                $contador++;
            }
        }    
    ?>
    <input name="frete" type="hidden" value="<?=get_frete()?>">
    
    <input name="email" type="hidden" value="<?=$_SESSION['email']?>" >
    <input name="cpf" type="hidden" value="<?=$_SESSION['cpf']?>">
    <input name="nome" type="hidden" value="<?=$_SESSION['name']?>">
    <input name="telefone" type="hidden" value="<?=$_SESSION['tel']?>">
    <input name="cep" type="hidden" value="<?=$_SESSION['sCepDestino']?>">
     
	<div align="center">
        <h1 align="center">Pagamento</h1>
        <p><a href="javascript:clear();" title="Clique aqui se não for você!"><?=$_SESSION['name']?></a>, o total da compra é </br><h3><?='R$ '.number_format(get_order_total(), 2, '.', ' ')?></h3></p>
        <table border="0" cellpadding="2px">            
            <tr><td>&nbsp;</td><td><input type="image" src="https://www.bcash.com.br/webroot/img/bt_comprar.gif" value="Pagar" alt="Pagar" border="0" align="absbottom" ></td></tr>
        </table>
	</div>
</form>

<script language="javascript">
	function clear(){
		if(confirm('Você realmente quer apagar os dados?')){
			document.form1.pid.value=pid;
			document.form1.command.value='clear';
			document.form1.submit();
		}
    }
</script>

<?}else if (is_array($_SESSION['cart'])){?>

<form name="form1" action="pagamento.php" method="POST">
    <input type="hidden" name="command" value="verify" />
	<div align="center">
        <h1 align="center">Seus Dados</h1>
        <div style="color:<?=$msg_color?>"><?=$msg?></div>
        <table border="0" cellpadding="2px">
            <tr><td>Nome:</td><td><input type="text" name="name" required value="<?=$_SESSION['name']?>"/></td></tr>           
            <tr><td>Sobrenome:</td><td><input type="text" name="sname" required value="<?=$_SESSION['sname']?>"/></td></tr>
            <tr><td>Email:</td><td><input type="email" name="email" required value="<?=$_SESSION['email']?>"/></td></tr>
            <tr><td>CPF:</td><td><input type="text" name="cpf" maxlength="14" placeholder="888.888.888-88" required
                    pattern="^(\d{3}\.\d{3}\.\d{3}-\d{2})|(\d{11})$"
                    onkeypress="return formatar('###.###.###-##', this, event)"
                    oninput="validar(this)" 
                    value="<?=$_SESSION['cpf']?>" /></td></tr>
            <tr><td>Telefone:</td><td><input name="telefone" type="tel" maxlength="12" placeholder="11-8888-8888" required
                    pattern="^(\d{2}\-\d{4}\-\d{4})|(\d{10})$"
                    onkeypress="return formatar('##-####-####', this, event)" 
                    value="<?=$_SESSION['tel']?>"/></td></tr>           
            <tr><td>&nbsp;</td><td><input type="submit" value="Conferir Dados" /></td></tr>
        </table>
	</div>
</form>

<script language="javascript">
    function validar(input) {
        if (!validarCPF(input.value)) {
            if(input.value=='') {
                input.setCustomValidity('Preencha este campo.');
            } else {
                input.setCustomValidity(input.value + ' é um CPF incorreto.');
            }
        } else {
            input.setCustomValidity('');
        }
    }
    
    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g,'');     
        if(cpf == '') return false;     
        // Elimina CPFs invalidos conhecidos
        if (cpf.length != 11 || 
            cpf == "00000000000" || 
            cpf == "11111111111" || 
            cpf == "22222222222" || 
            cpf == "33333333333" || 
            cpf == "44444444444" || 
            cpf == "55555555555" || 
            cpf == "66666666666" || 
            cpf == "77777777777" || 
            cpf == "88888888888" || 
            cpf == "99999999999")
            return false;         
        // Valida 1o digito
        add = 0;
        for (i=0; i < 9; i ++)
            add += parseInt(cpf.charAt(i)) * (10 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(9)))
            return false;         
        // Valida 2o digito
        add = 0;
        for (i = 0; i < 10; i ++)
            add += parseInt(cpf.charAt(i)) * (11 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(10)))
            return false;             
        return true;        
    }
    
    function formatar(mascara, documento, evt){           
      var i = documento.value.length;
      var saida = mascara.substring(0,1);
      var texto = mascara.substring(i)      
      if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
      }
      return isNumber(evt);      
    }
    
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

</script>

<?} else { header("location:catalogo.php"); }?>

</body>
</html>
