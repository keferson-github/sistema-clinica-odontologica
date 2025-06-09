<?php

//definir fuso horário
date_default_timezone_set('America/Sao_Paulo');

$url_sistema = "https://$_SERVER[HTTP_HOST]/";
$url = explode("//", $url_sistema);
if ($url[1] == 'localhost/') {
	$url_sistema = "http://$_SERVER[HTTP_HOST]/odonto/";
}

//dados conexão bd local
$servidor = 'localhost';
$banco = 'odonto';
$usuario = 'root';
$senha = '';

try {
	$pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");
} catch (Exception $e) {
	echo 'Erro ao conectar ao banco de dados!<br>';
	echo $e;
}


//variaveis globais
$nome_sistema = 'Sistema Clínica Odontológica';
$email_sistema = 'contato@techsolutionspro.com.br';
$telefone_sistema = '(11) 93703-9913';
$instagram_sistema = 'techsolutionspro';


$query = $pdo->query("SELECT * from config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas == 0) {
	$pdo->query("INSERT INTO config SET nome = '$nome_sistema', email = '$email_sistema', telefone = '$telefone_sistema', logo = 'logo.png', logo_rel = 'logo.jpg', icone = 'icone.png', ativo = 'Sim', multa_atraso = '0', juros_atraso = '0', marca_dagua = 'Sim', assinatura_recibo = 'Não', impressao_automatica = 'Não', api_whatsapp = 'Não', alterar_acessos = 'Não', abertura_caixa = 'Sim', horas_confirmacao = '24'");
} else {
	$nome_sistema = $res[0]['nome'];
	$email_sistema = $res[0]['email'];
	$telefone_sistema = $res[0]['telefone'];
	$endereco_sistema = $res[0]['endereco'];
	$instagram_sistema = $res[0]['instagram'];
	$logo_sistema = $res[0]['logo'];
	$logo_rel = $res[0]['logo_rel'];
	$icone_sistema = $res[0]['icone'];
	$ativo_sistema = $res[0]['ativo'];
	$multa_atraso = $res[0]['multa_atraso'];
	$juros_atraso = $res[0]['juros_atraso'];
	$marca_dagua = $res[0]['marca_dagua'];
	$assinatura_recibo = $res[0]['assinatura_recibo'];
	$impressao_automatica = $res[0]['impressao_automatica'];
	$cnpj_sistema = $res[0]['cnpj'];
	$entrar_automatico = $res[0]['entrar_automatico'];
	$mostrar_preloader = $res[0]['mostrar_preloader'];
	$ocultar_mobile = $res[0]['ocultar_mobile'];
	$api_whatsapp = $res[0]['api_whatsapp'];
	$token_whatsapp = $res[0]['token_whatsapp'];
	$instancia_whatsapp = $res[0]['instancia_whatsapp'];
	$alterar_acessos = $res[0]['alterar_acessos'];
	$dados_pagamento = $res[0]['dados_pagamento'];
	$abertura_caixa = $res[0]['abertura_caixa'];
	$horas_confirmacao = $res[0]['horas_confirmacao'];

	$tel_whats = '55' . preg_replace('/[ ()-]+/', '', $telefone_sistema);

	if ($ativo_sistema != 'Sim' and $ativo_sistema != '') { ?>
		<style type="text/css">
			@media only screen and (max-width: 700px) {
				.imgsistema_mobile {
					width: 300px;
				}

			}
		</style>
		<div style="text-align: center; margin-top: 100px">
			<img src="<?php echo $url_sistema ?>img/bloqueio.png" class="imgsistema_mobile">
		</div>
		<?php
		exit();
	}

}
?>