<?php
require_once("conexao.php");
@session_start();

$query = $pdo->query("SELECT * from usuarios where nivel = 'Administrador' and ativo = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
$senha = '123';
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);
if ($linhas == 0) {
	$pdo->query("INSERT INTO usuarios SET nome = 'Seu Nome', email = '$email_sistema', senha = '', senha_crip = '$senha_crip', nivel = 'Administrador', ativo = 'Sim', foto = 'sem-foto.jpg', telefone = '$telefone_sistema', data = curDate(), mostrar_registros = 'Sim' ");
}

?>



<!DOCTYPE html>
<html lang="pt-BR">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
	<!-- META DATA -->
	<meta property="og:title" content="Sistema Para Dentistas" />
    <meta property="og:url" content="https://odonto.grupowebsystem.com.br/" />
    <meta property="og:image" content="https://img.freepik.com/fotos-gratis/vista-da-sala-de-aula-futurista-e-de-alta-tecnologia-para-estudantes_23-2150906200.jpg?t=st=1734038306~exp=1734041906~hmac=2e42c66e8064e046ec1e6fcca2068a37962e1efc5b893cd3ace28a5ad3e4883c&w=740" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <meta property="og:site_name" content="Tech Solutions Pro LTDA" />
    
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="Sistema Para Clínicas Odontológicas">
	<meta name="Author" content="Tech Solutions Pro">
	<meta name="Keywords" content="sistemas para clínicas odontológicas, sistemas para dentístas, software para dentistas" />

	<!-- TITLE -->
	<title>Sistema Odonto</title>


	<link rel="icon" href="img/icone.png" type="image/x-icon" />
	<link href="assets/css/icons.css" rel="stylesheet">
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/custom.css" rel="stylesheet">
	<link href="assets/css/style-dark.css" rel="stylesheet">
	<link href="assets/css/style-transparent.css" rel="stylesheet">
	<link href="assets/css/skin-modes.css" rel="stylesheet" />
	<link href="assets/css/animate.css" rel="stylesheet">




</head>


<!-- GLOBAL-LOADER -->
<div id="global-loader">
	<img src="img/loader.gif" class="loader-img loader loader_mobile" alt="">
</div>
<!-- /GLOBAL-LOADER -->

<body class="ltr error-page1 bg-primary" id="pagina">


	<div class="square-box">
		
	</div>


	<div class="page">


		<div class="page-single">
			<div class="container">
				<div class="row">
					<div
						class="col-xl-5 col-lg-6 col-md-8 col-sm-8 col-xs-10 card-sigin-main mx-auto my-auto py-4 justify-content-center">
						<div class="card-sigin">
							<!-- Demo content-->
							<div class="main-card-signin d-md-flex">
								<div class="wd-100p">
									<div class="d-flex mb-4 justify-content-center"><a href="index.php">
											<img src="img/logo.png" class="sign-favicon" alt="logo" width="250px"></a></div>
									<div class="">
										<div class="main-signup-header">

											<div class="panel panel-primary">

												<div class="panel-body tabs-menu-body border-0 p-3">

													<?php
													if (isset($_SESSION['msg'])) {

														echo '<div class="alert alert-danger mg-b-0 mb-3 alert-dismissible fade show" role="alert">
											<strong><span class="alert-inner--icon"><i class="fe fe-slash"></i></span></strong> ' . $_SESSION['msg'] . '!
											<button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"><span aria-hidden="true">&times;</span></button>
										</div>';

														unset($_SESSION['msg']);
													}
													?>




													<form method="post" action="autenticar.php">
														<label>Usuário</label>
														<div class="input-group mb-4">
															<div class="input-group-text">
																<i class="fe fe-at-sign"></i>
															</div>
															<input type="text" class="form-control" name="usuario" id="usuario"
																placeholder="Digite o Usuário" required="">
														</div>
														<div class="form-group">
															<label class="control-label">Senha</label>
															<div class="input-group mb-4">
																<div class="input-group-text">
																	<span toggle="#password-field" class="fa fa-fw fa-eye  toggle-password"></span>
																</div>
																<input id="password-field" type="password" class="form-control" name="senha"
																	placeholder="Digite sua Senha" required value="">
															</div>
														</div>

														<div class="form-group" style="margin-left: 22px">
															<span><input class="form-check-input" type="checkbox" value="Sim" name="salvar"
																	id="salvar_acesso"></span>
															<span class="control-label" style="margin-top:5px">Salvar Acesso</span>
														</div>

														<button class="btn btn-primary btn-block">Entrar no Sistema</button>

													</form>

												</div>
											</div>

											<div class="main-signin-footer text-center mt-3">

												<p><a href="" class="mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Esqueceu sua
														Senha?</a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>





</body>

</html>




<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="exampleModalLabel">Recuperar Senha</h5>
				<button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true"
						class="text-white">&times;</span></button>

			</div>
			<form method="post" id="form-recuperar">
				<div class="modal-body">
					<label for="recipient-name" class="col-form-label">Email:</label>
					<input placeholder="Digite seu Email" class="form-control" type="email" name="email" id="email-recuperar"
						required>

					<br>
					<small>
						<div id="mensagem-recuperar" align="center"></div>
					</small>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
					<button type="submit" class="btn btn-primary">Recuperar Senha</button>
				</div>
			</form>
		</div>
	</div>
</div>


<form action="autenticar.php" method="post" style="display:none">
	<input type="text" name="id" id="id_usua">
	<input type="text" name="pagina" id="pagina_salva">
	<button type="submit" id="btn_auto"></button>
</form>


<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/plugins/moment/moment.js"></script>
<script src="assets/js/eva-icons.min.js"></script>
<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/js/themecolor.js"></script>
<script src="assets/js/custom.js"></script>


<script type="text/javascript">
	$(document).ready(function () {

		var email_usuario = localStorage.email_usu;
		var senha_usuario = localStorage.senha_usu;
		var id_usuario = localStorage.id_usu;
		var pagina = localStorage.pagina;

		var redirecionar = "<?= $entrar_automatico ?>";

		if (id_usuario != "" && id_usuario != undefined && redirecionar == 'Sim') {
			$('#pagina').hide();
			$('#id_usua').val(id_usuario);
			$('#pagina_salva').val(pagina);
			$('#btn_auto').click();
		} else {
			$('#pagina').show();
		}

		if (email_usuario != "" && email_usuario != undefined) {
			$('#salvar_acesso').prop('checked', true);
		} else {
			$('#salvar_acesso').prop('checked', false);
		}

		$('#usuario').val(email_usuario);
		$('#password-field').val(senha_usuario);

	});
</script>


<script>
	$(".toggle-password").click(function () {

		$(this).toggleClass("fa-eye fa-eye-slash");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});
</script>




<script type="text/javascript">
	$("#form-recuperar").submit(function () {

		$('#mensagem-recuperar').text('Enviando!!');

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "recuperar-senha.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-recuperar').text('');
				$('#mensagem-recuperar').removeClass()
				if (mensagem.trim() == "Recuperado com Sucesso") {

					$('#email-recuperar').val('');
					$('#mensagem-recuperar').addClass('text-success')
					$('#mensagem-recuperar').text('Sua Senha foi enviada para o Email')

				} else {

					$('#mensagem-recuperar').addClass('text-danger')
					$('#mensagem-recuperar').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>