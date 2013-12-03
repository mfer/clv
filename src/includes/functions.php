<?
    function no_more_than($a, $b){
        if ( $a <= $b){
            return $a;
        }else{
            return $b;
        }
    }
	function get_product_stock($pid){
		#$result=mysql_query("select nome from produto where sku=$pid");
		#$row=mysql_fetch_array($result);
		#return $row['nome'];
        return 10;
	}
	function get_product_nome($pid){
		$result=mysql_query("select nome from produto where sku=$pid");
		$row=mysql_fetch_array($result);
		return $row['nome'];
	}
	function get_preco($pid){
		$result=mysql_query("select preco from produto where sku=$pid");
		$row=mysql_fetch_array($result);
		return $row['preco'];
	}
	function remove_product($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				unset($_SESSION['cart'][$i]);
				break;
			}
		}
		$_SESSION['cart']=array_values($_SESSION['cart']);
	}
	function get_order_total(){
		$max=count($_SESSION['cart']);
		$sum=0;
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$preco=get_preco($pid);
			$sum+=$preco*$q;
		}
		return $sum;
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
		return mysql_query("SELECT * FROM `produto` ORDER BY `nome`");
	}

?>
