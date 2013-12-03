<?
	include("includes/db.php");

  // Obtenha seu TOKEN entrando no menu Ferramentas do Bcash
  $bcash_token = '189418A59EAE7662C5FD84A380690C14';

  /* Montando as variáveis de retorno */
  $id_transacao = $_POST['id_transacao'];
  $data_transacao = $_POST['data_transacao'];
  $data_credito = $_POST['data_credito'];
  $valor_original = $_POST['valor_original'];
  $valor_loja = $_POST['valor_loja'];
  $valor_total = $_POST['valor_total'];
  $desconto = $_POST['desconto'];
  $acrescimo = $_POST['acrescimo'];
  $tipo_pagamento = $_POST['tipo_pagamento'];
  $parcelas = $_POST['parcelas'];
  $cliente_nome = $_POST['cliente_nome'];
  $cliente_email = $_POST['cliente_email'];
  $cliente_rg = $_POST['cliente_rg'];
  $cliente_data_emissao_rg = $_POST['cliente_data_emissao_rg'];
  $cliente_orgao_emissor_rg = $_POST['cliente_orgao_emissor_rg'];
  $cliente_estado_emissor_rg = $_POST['cliente_estado_emissor_rg'];
  $cliente_cpf = $_POST['cliente_cpf'];
  $cliente_sexo = $_POST['cliente_sexo'];
  $cliente_data_nascimento = $_POST['cliente_data_nascimento'];
  $cliente_endereco = $_POST['cliente_endereco'];
  $cliente_complemento = $_POST['cliente_complemento'];
  $cliente_telefone = $_POST['cliente_telefone'];
  $status = $_POST['status'];
  $cod_status = $_POST['cod_status'];
  $cliente_bairro = $_POST['cliente_bairro'];
  $cliente_cidade = $_POST['cliente_cidade'];
  $cliente_estado = $_POST['cliente_estado'];
  $cliente_cep = $_POST['cliente_cep'];
  $frete = $_POST['frete'];
  $tipo_frete = $_POST['tipo_frete'];
  $informacoes_loja = $_POST['informacoes_loja'];
  $id_pedido = $_POST['id_pedido'];
  $free = $_POST['free'];

  /* Essa variável indica a quantidade de produtos retornados */
  $qtde_produtos = $_POST['qtde_produtos'];

  /* Verificando ID da transação */
  /* Verificando status da transação */
  /* Verificando valor original */
  /* Verificando valor da loja */
  $post = "transacao=$id_transacao" . "&status=$status" . "&cod_status=$cod_status" . "&valor_original=$valor_original" . "&valor_loja=$valor_loja" . "&token=$bcash_token";
  $enderecoPost = "https://www.bcash.com.br/checkout/verify/";

  ob_start();
  $ch = curl_init();
  curl_setopt ($ch, CURLOPT_URL, $enderecoPost);
  curl_setopt ($ch, CURLOPT_POST, 1);
  curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
  curl_exec ($ch);
  $resposta = ob_get_contents();
  ob_end_clean(); 

  
    if(trim($resposta)=="VERIFICADO")
    {
        $qexiste = "SELECT * FROM `transacao` WHERE `idexterno`='$id_transacao';";
        $rexiste = mysql_query ($qexiste);

        if(mysql_num_rows($rexiste) == 0){            
            for ($x=1; $x <= $qtde_produtos; $x++) 
            {
                $produto_codigo = $_POST['produto_codigo_'.$x];
                $produto_descricao = $_POST['produto_descricao_'.$x];
                $produto_qtde = $_POST['produto_qtde_'.$x];
                $produto_valor = $_POST['produto_valor_'.$x];
                $produto_extra = $_POST['produto_extra_'.$x];

                $qstq = "UPDATE `produto` SET quantidadeestoque=quantidadeestoque-'$produto_qtde' WHERE sku='$produto_codigo';";
                $rstq = mysql_query ($qstq);
            }
        }

        $q = "INSERT INTO `transacao` (`id`, `idexterno`, `valor`, `codigostatus`) VALUES ('$id_pedido', '$id_transacao', '$valor_original', '1');";
        $r = mysql_query ($q);
    }

    unset($_SESSION['chave']);    
    unset($_SESSION['cart']);
    
            //SMTP needs accurate times, and the PHP time zone MUST be set
            //This should be done in your php.ini, but this is how to do it if you don't have access to that
            date_default_timezone_set('America/Sao_Paulo');

            require 'includes/PHPMailerAutoload.php';           

            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = 'smtp.gmail.com';
            //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
            $mail->Port = 587;
            //Set the encryption system to use - ssl (deprecated) or tls
            $mail->SMTPSecure = 'tls';
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication - use full email address for gmail
            $mail->Username = "myifttt.mfer@gmail.com";
            //Password to use for SMTP authentication
            $mail->Password = "myifttt.mfer@";
            //Set who the message is to be sent from
            $mail->setFrom('myifttt.mfer@gmail.com', 'myifttt mfer');
            //Set an alternative reply-to address
            $mail->addReplyTo('fco.manah@gmail.com', 'fco manah');
            //Set who the message is to be sent to
            $mail->addAddress($cliente_email, $cliente_nome);
            //Set the subject line
            $mail->Subject = 'CLV';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML('Transação iniciada! 
                Clique nesse <a href="http://localhost/ibd/andamento.php?command=read&tidbcash='.$id_transacao.'">link</a>
                 para acompanhar o status do seu pedido. Cordialmente,CLV');
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            #$mail->addAttachment('images/phpmailer_mini.gif');
            

            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
                $data['success'] = false;
            } else {
                //echo "Message sent!";
                $data['success'] = true;
            }
    
    $url = 'Location: andamento.php?command=read&tidbcash='.$id_transacao;
    header($url);

?>
