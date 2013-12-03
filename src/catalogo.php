<?
	include("includes/db.php");
	include("includes/carrinho_functions.php");
    init_sessao();
	
	if($_REQUEST['command']=='add' && $_REQUEST['productid']>0){
		$pid=$_REQUEST['productid'];
		addtocart($pid,1);
		header("location:carrinho.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Catálogo</title>
</head>
<body>
<form name="form1">
	<input type="hidden" name="productid" />
    <input type="hidden" name="command" />
</form>
<div align="right">
    <a style="text-decoration: none" href="catalogo.php">Catálogo</a>
    <a style="text-decoration: none" href="carrinho.php">Carrinho</a>
    <a style="text-decoration: none" href="pagamento.php">Pagamento</a>
</div>
<div align="center">
	<h1 align="center">Catálogo</h1>
	<table border="0" cellpadding="2px" width="600px">
		<?
			$result=get_all_products();
			while($row=mysql_fetch_row($result)){
		?>
    	<tr>
        	<td><img src="<?='data:image/jpg;base64,'.$row[6]?>" /></td>            
            <td>
                <b><?=$row[2]?></b><br/>
                <?=$row[3]?><br />
                Preço:<big style="color:green"> R$ <?=number_format($row[4], 2, '.', ' ')?></big><br /><br />
                <a style="text-decoration: none" href="catalogo.php?command=add&productid=<?=$row[1]?>">
                <input type="button" value="Adicionar ao Carrinho"/></a>
			</td>
		</tr>
        <tr><td colspan="2"><hr size="1" /></td>
        <? } ?>
    </table>
</div>
</body>
</html>
