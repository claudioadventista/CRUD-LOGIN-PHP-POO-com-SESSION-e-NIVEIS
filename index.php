<?php
	@session_start();
	    require_once'class.php';
		$conn = BancoDados::coneccaoUNICA();
		$contaGerente = classes::contagem();
		
		 // cadastra um gerente, com login admin e senha admin, caso não haja nenhum cadastrado	 
		 if($contaGerente == 0){
			classes::cadastrandoGerente();
	  	 }
		 
		$usuario = classes::lista();
		
		if ($conn){
			echo "Conectado com o banco ";
		};
	
		if(isset($_SESSION['logado'])){
			echo "  - Logado como : ".'<span class="logado">'.($_SESSION['logado']).'</span>'.
			"  - Nível  : ".'<span class="logado">'.($_SESSION['nivel']).'</span>'.
			'<style> .formLogin{display:none;}</style>
			<a href="chamadaLogin.php?deslogar=0" class="deslogar" >Deslogar</a>';
		
		}else{
			echo'<span class="deslogado" >Você não está logado. </span>
			<style>.mostraNivel{display:none;}</style>';	
		}
		
		if((isset($_SESSION['nivel'])AND($_SESSION['nivel'] == "Gerente"))) {
			echo'<style>.formCadastro{display:block;}</style>';
		}else{
			echo'<style>.formCadastro{display:none;}</style>';
		}
	echo'<hr>';
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Document</title>
		<script type="text/javascript" src="ajax.js"></script>
		<link rel="stylesheet" type="text/css" href="style.css" />

	</head>
	<body>
		
		<div class="mostraNivel">
			<h1>Cadastrados</h1>
		</div>
		
	<?php 
		if((isset($_SESSION['nivel'])AND($_SESSION['nivel'] == "Usuário comum"))) {
			   echo "Seu ID :-------- ".$_SESSION['id'].'<br>'.
		            "Seu Nome :--- ".$_SESSION['nome'].'<br>'.
					"Seu Login :---- ".$_SESSION['logado'].'<br>'.
					"Sua Senha :-- ".$_SESSION['senha'].'<br>'.
					"Seu Nível :---- ".$_SESSION['nivel'];	
		}
		
	   if((isset($_SESSION['nivel'])AND(($_SESSION['nivel'] == "Administrador")OR($_SESSION['nivel'] == "Gerente")))) {
				foreach ($usuario as $aluno){
				// Pegar todos os valores de um array, quando há mais de um resultado	
				if(($_SESSION['nivel']=="Gerente")OR(($aluno -> nivel == 0)OR($aluno -> nivel ==1))){
					if($aluno -> nivel == 0){
						$mostraNivel = "Usuário Comum";
					}
					else if($aluno -> nivel == 1){
						$mostraNivel = "Administrador";
					}
					else {
						$mostraNivel = "Gerente";
					};
									
					echo "( ID ) : -------- ".$aluno -> id.'<br>'.
		            " ( Nome ) : --- ".$aluno -> nome.'<br>'.
					" ( Login ) : ---- ".$aluno -> login.'<br>'.
					" ( Senha ) : -- ".$aluno -> senha.'<br>'.
					" ( Nível ) : ---- ".$mostraNivel.'<br>';	
	?>
					<br>
						<a href="alterar.php?idUnico=<?php echo $aluno->id; ?>" >Alterar</a>
					
	<?php 
					}
					if ($_SESSION['nivel']=="Gerente"){	
						if($aluno->id <> $_SESSION['id']){ // impede excluir a sí mesmo ?>
							<a style="margin-left:80px;" href="chamadaLogin.php?excluir=<?php echo $aluno->id; ?> " OnClick="return confirm('Confirma Exclusão?')" >Excluir</a>
	<?php 				} 
					} 
	?>
					<br>
	<?php
					echo'--------------------------------------------------------'.'<br>';			
				}
		}

		if((isset($_SESSION['mensagem']))AND($_SESSION['mensagem']<>"")){
			// Bloco com html e javascript mostrado com php, usando echo
			echo'<script type="text/javascript">
				setTimeout(function() {
				document.getElementById("mensagem").style.display="none";
				}, 3000);
			</script>'.
			'<div class="mensagem" id="mensagem">'.
		    	$_SESSION["mensagem"].
			'</div>';
			unset($_SESSION['mensagem']);
			};
	?>

		<div id="formLogin" class="formLogin">
			<h1 align=center>Entrar</h1>
			<form name="form" action="chamadaLogin.php" method="POST">		
				Login  
				<input type="text"  name="login" autocomplete="off" ><br><br>	
				Senha 
				<input type="password"  name="senha" autocomplete="off" ><br><br><br><br>
				<input type="submit" class="botao" value="L o g a r"/>
				<br><br>
				<a style="margin-left:30px;" href="#" onclick="esqueciSenha()">Esqueci  a  senha</a>			
			</form>
		</div>
		
		<div  id="formCadastro"  class="formCadastro corCadastro">
			<h1 align=center>Cadastrar</h1>
			<form name="form" action="chamadaLogin.php" method="POST">		
				Nome  
				<input type="text"  name="nomeCad" autocomplete="off"placeholder="Digite o nome completo" required  ><br><br>
				Login  
				<input type="text"  name="loginCad" autocomplete="off" placeholder="Digite o login" required ><br><br>		
				Senha 
				<input type="password"  name="senhaCad" autocomplete="off" placeholder="Digite a senha" required ><br><br>
				Nível<br>
				 <select  name="nivelCad" >
					<option value="0"> Usuário Comum </option> 
					<option value="1"> Administrador </option> 
					<option value="2"> Gerente </option> 
				</select><br><br><br>
				<input type="submit" class="botao" value="C a d a s t r a r"/>	
			</form>
		</div>
		
		<div id="formRecSenha" class="formRecSenha">
			<h1 align=center>Recuperar</h1>
			<form name="form" action="chamadaLogin.php" method="POST">			
				Nome completo 
				<input type="text"  name="nomeRec" autocomplete="off" ><br><br>	
				Nova Senha 
				<input type="password"  name="senhaRec" autocomplete="off" ><br><br>
				Repita a Senha 
				<input type="password"  name="repitaSenhaRec" autocomplete="off" >
				<br><br><br><br>
				<input type="submit" class="botao" value="C o n s u l t a r"/>
				<br><br>
				<a style="margin-left:65px;" href="#" onclick="fecharEsqueci()">Voltar</a>			
				<br><br>		
			</form>
		</div>
		<script>
			function esqueciSenha() {
				document.getElementById("formRecSenha").style.display = "block";
				document.getElementById("formLogin").style.display = "none";
			};

			function fecharEsqueci() {
				document.getElementById("formRecSenha").style.display = "none";
				document.getElementById("formLogin").style.display = "block";
			};
		</script>
</body>
</html>