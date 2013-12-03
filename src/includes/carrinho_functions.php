<?
    function getBrowser() 
    { 
        $u_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
        
        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
        } 
        elseif(preg_match('/Firefox/i',$u_agent)) 
        { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
        } 
        elseif(preg_match('/Chrome/i',$u_agent)) 
        { 
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
        } 
        elseif(preg_match('/Safari/i',$u_agent)) 
        { 
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
        } 
        elseif(preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
        } 
        elseif(preg_match('/Netscape/i',$u_agent)) 
        { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
        } 
        
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }
        
        // check if we have a number
        if ($version==null || $version=="") {$version="?";}
        
        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    } 

    function create_sessao(){
        $ua=getBrowser();
        $browser = $ua['name'] . " " . $ua['version'];
        $ip = $_SERVER['REMOTE_ADDR'];
   		$result=mysql_query("INSERT INTO `sessao` (`browser`, `ip`) VALUES ('$browser', '$ip' );");
        return mysql_insert_id();
    }
    
    function init_sessao(){
        if (isset($_COOKIE['SESSION'])) 
        {
            $chave = $_COOKIE['SESSION'];
        } 
        else 
        {
            $chave = md5(uniqid('biped',true));
            $_SESSION['chave'] = create_sessao();
        }
        setcookie('SESSION', $chave, time()+(60*60*24*30));
        
        if(!isset($_SESSION['chave'])) $_SESSION['chave'] = create_sessao();
    }
    
    function get_frete(){

        require_once('RsCorreios.php');

        // Instancia a classe
        $frete = new RsCorreios();

        // Percorre todos as variáveis $_POST para setar os atributos necessários
        // Se você achar melhor pode fazer 1 a 1.
        // Ex.: $frete->setValue('sCepOrigem', $_POST['sCepOrigem']);
        //foreach ($_POST as $key => $value) {
        //    $frete->setValue($key, $value);
        //}
        
        $frete->setValue('sCepDestino', $_SESSION['sCepDestino']);
        $frete->setValue('nCdServico', $_SESSION['nCdServico']);
        
        $frete->setValue('sCepOrigem', "31050540");
        $frete->setValue('nVlPeso', "1");
        $frete->setValue('nVlAltura', "2");
        $frete->setValue('nVlLargura', "11");
        $frete->setValue('nVlComprimento', "16");

        // Diâmetro
        $frete->getDiametro();

        // Chamado ao método getFrete, que irá se comunicar com os correios
        // e nos trazer o resultado
        $result = $frete->getFrete();


        // Retornamos a mensagem de erro caso haja alguma falha
        if ($result['erro'] != 0) {
            $resultadoFrete = $result['msg_erro'];
            return '';
        }
        // Caso não haja erros mostramos o resultado de cada variável retornada pelos correios.
        // Use apenas as que forem de seu interesse
        else {
            
            $resultadoFrete = "Código do Serviço: " . $result['servico_codigo'] . "<br />";
            $resultadoFrete .= "Valor do Frete: R$ " . $result['valor'] . "<br />";
            $resultadoFrete .= "Prazo de Entrega: " . $result['prazo_entrega'] . " dias <br />";
            $resultadoFrete .= "Valor p/ Mão Própria: R$ " . $result['mao_propria'] . "<br />";
            $resultadoFrete .= "Valor Aviso de Recebimento: R$ " . $result['aviso_recebimento'] . "<br />";
            $resultadoFrete .= "Valor Declarado: R$ " . $result['valor_declarado'] . "<br />";
            $resultadoFrete .= "Entrega Domiciliar: " . $result['en_domiciliar'] . "<br />";
            $resultadoFrete .= "Entrega Sábado: " . $result['en_sabado'] . "<br />";
            
            return str_replace(array(','), array('.'), $result['valor']);
        }

        //echo $resultadoFrete;
        
    }
    
    function no_more_than($a, $b){
        if ( $a <= $b){
            return $a;
        }else{
            return $b;
        }
    }
	function get_product_stock($pid){
		$result=mysql_query("select quantidadeestoque from produto where sku=$pid");
		$row=mysql_fetch_array($result);
		return $row['quantidadeestoque'];
	}
	function get_product_name($pid){
		$result=mysql_query("select nome from produto where sku=$pid");
		$row=mysql_fetch_array($result);
		return $row['nome'];
	}
	function get_price($pid){
		$result=mysql_query("select preco from produto where sku=$pid");
		$row=mysql_fetch_array($result);
		return $row['preco'];
	}
	function get_description($pid){
		$result=mysql_query("select descricao from produto where sku=$pid");
		$row=mysql_fetch_array($result);
		return $row['descricao'];
	}

	function get_order_total(){
		$max=count($_SESSION['cart']);
		$sum=0;
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$preco=get_price($pid);
			$sum+=$preco*$q;
		}
        if(get_frete()==''){
            return $sum;
        }else{
            return $sum+get_frete();
        }
	}

	function del_cart($pid){
        //$chave = $_SESSION['chave'];
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				unset($_SESSION['cart'][$i]);
                //$result=mysql_query("DELETE FROM `carrinho` WHERE `chave` = '$chave' AND `sku` = '$pid';");
				break;
			}
		}
		$_SESSION['cart']=array_values($_SESSION['cart']);
	}
    
	function update_cart(){
        $chave = $_SESSION['chave'];
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=intval($_REQUEST['product'.$pid]);
			if($q>0 && $q<=10000){
                if($_SESSION['cart'][$i]['qty']!=$q){
                    $_SESSION['cart'][$i]['qty']=$q;
                    $result=mysql_query("UPDATE `carrinho` SET `quantidade` = '$q', `modificacao` = now() WHERE `chave` = '$chave' AND `sku` = '$pid';");
                }
			}else{                
				#$msg='Algum produto não foi atualizado! Quantidade deve ser um número entre 1 e 999';
			}
		}
    }
	function addtocart($pid,$q){
        
		if($pid<1 or $q<1) return;
		
		if(is_array($_SESSION['cart'])){
			if(product_exists($pid)) return;
			$max=count($_SESSION['cart']);
			$_SESSION['cart'][$max]['productid']=$pid;
			$_SESSION['cart'][$max]['qty']=$q;
		}
		else{
			$_SESSION['cart']=array();
			$_SESSION['cart'][0]['productid']=$pid;
			$_SESSION['cart'][0]['qty']=$q;
		}
        
        //vai falhar se o produto
        $chave = $_SESSION['chave'];
		$result=mysql_query("INSERT INTO `carrinho` (`chave`, `sku`, `quantidade`) VALUES ('$chave', '$pid', '$q');");
	}
    
	function product_exists($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		$flag=0;
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				$flag=1;
				break;
			}
		}
		return $flag;
	}
    
    function get_all_products(){
		return mysql_query("SELECT * FROM `produto` NATURAL JOIN `imagens` WHERE `quantidadeestoque`>0 ORDER BY `nome`");
	}

?>
