<?
    include("auth_functions.php");

	function get_transaction($tidbcash){
        
          	$bcash_email = 'manaphys@gmail.com';
            $bcash_token = '189418A59EAE7662C5FD84A380690C14';  
      
          $urlPost = 'https://www.pagamentodigital.com.br/transacao/consulta/'; 
          $transacaoId = $tidbcash;
          $pedidoId = '';
          $tipoRetorno = '2'; 
          $codificacao = '1'; 

          ob_start(); 
            $ch = curl_init(); 
              curl_setopt($ch, CURLOPT_URL, $urlPost); 
              curl_setopt($ch, CURLOPT_POST, 1); 
              curl_setopt($ch, CURLOPT_POSTFIELDS,array('id_transacao'=>$transacaoId, 'id_pedido'=>$pedidoId,'tipo_retorno'=>$tipoRetorno,'codificacao'=>$codificacao)); 
              curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($bcash_email.':'.$bcash_token))); 
            curl_exec($ch); 
            $json = ob_get_contents(); 
          ob_end_clean(); 
          

          /* Capturando o http code para tratamento dos erros na requisição*/  
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
          curl_close($ch); 

          if($httpCode != '200')
          { 
           switch ($httpCode) 
            {
              case 400:
                echo 'Requisição com parâmetros obrigatórios vazios ou inválidos';
                break;
              case 401:
                echo 'Falha na autenticação ou sem acesso para usar o serviço';
                break;
              case 405:
                echo 'Método não permitido, o serviço suporta apenas POST';
                break;
              case 500:
                echo 'Erro fatal na aplicação, executar a solicitação mais tarde';
                break;
            }
          }
          else
          {
            /*
            echo '<pre>';
              echo '$json is ';
              if(!is_array($json)) echo 'not ';
              echo 'an array:</br>';
              var_dump($json);
            echo '</pre>';
            */
            
            $obj = json_decode($json,true);
            
            /*
            echo '<pre>';
              echo '$obj is ';
              if(!is_array($obj)) echo 'not ';
              echo 'an array:</br>';
              var_dump($obj);
            echo '</pre>';
            exit();
            */
            
            
            //echo_iterative($obj);
           
            //echo_recursive($obj, '');
            
            //echo get_iterative($obj);
            
            //get_recursive($obj, '', $str);
            //echo $str;

            /*
            echo $obj['transacao']['data_transacao'];
            echo '</br>';    
            echo $obj['transacao']['id_transacao'];
            echo '</br>';
            echo $obj['transacao']['status'];
            echo '</br></br>';
            
            $i=0;
            while(isset($obj['transacao']['pedidos'][$i]['nome_produto']))
            {
              echo $obj['transacao']['pedidos'][$i]['nome_produto'];
              echo '</br>';
              echo $obj['transacao']['pedidos'][$i]['qtde'];
              echo '</br>';
              echo $obj['transacao']['pedidos'][$i]['valor_total'];
              echo '</br></br>';
              ++$i;
            }
            */
            
            $trs = array();

            $trs['nome'] = $obj['transacao']['cliente_nome'];
            $trs['email'] = $obj['transacao']['cliente_email'];
            $trs['telefone'] = $obj['transacao']['cliente_telefone'];
            $trs['endereco'] = $obj['transacao']['cliente_endereco'];
            $trs['complemento'] = $obj['transacao']['cliente_complemento'];
            $trs['bairro'] = $obj['transacao']['cliente_bairro'];
            $trs['cidade'] = $obj['transacao']['cliente_cidade'];
            $trs['estado'] = $obj['transacao']['cliente_estado'];
            $trs['cep'] = $obj['transacao']['cliente_cep'];
            
            
            $trs['data_transacao'] = $obj['transacao']['data_transacao'];
            $trs['id_transacao'] = $obj['transacao']['id_transacao'];
            $trs['id_pedido'] = $obj['transacao']['id_pedido'];
            $trs['status'] = $obj['transacao']['status'];
            
            $i=0;
            while(isset($obj['transacao']['pedidos'][$i]['nome_produto']))
            {
              $trs[$i]['codigo_produto'] = $obj['transacao']['pedidos'][$i]['codigo_produto'];
              $trs[$i]['nome_produto'] = $obj['transacao']['pedidos'][$i]['nome_produto'];
              $trs[$i]['qtde'] = $obj['transacao']['pedidos'][$i]['qtde'];
              $trs[$i]['valor_total'] = $obj['transacao']['pedidos'][$i]['valor_total'];
              ++$i;
            }
            $trs['qtd_prds'] = $i;
            
            return $trs;
        }
	}
?>
