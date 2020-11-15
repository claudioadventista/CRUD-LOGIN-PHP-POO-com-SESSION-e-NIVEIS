<?php
	@session_start();
	    require_once'class.php';
		
		$conn = BancoDados::coneccaoUNICA();
		$usuarioUnico = classes::listandoUnico($_GET['idUnico']);
		
	// Pega todos os valores do array, so serve se tiver apenas um resultado
		     $id = $usuarioUnico[0]->id;
			 $nome = $usuarioUnico[0]->nome;
			 $login = $usuarioUnico[0]->login;
			 $senha = $usuarioUnico[0]->senha;
			 $nivel = $usuarioUnico[0]->nivel;
		    
		if($nivel == 0){$niveis ="Usuário Comum";}
			else if($nivel == 1){$niveis = "Administrador";}		
			else {$niveis = "Gerente";}
		
		if ($conn){
			echo "Conectado com o banco ";
		};
	
	if(isset($_SESSION['logado'])){
		echo "  - Logado como : ".'<span class="logado">'.($_SESSION['logado']).'</span>'.
		"  - Nível  : ".'<span class="logado">'.($_SESSION['nivel']).'</span>'.
		'<style> .formLogin{display:none;}</style>
		<a href="chamadaLogin.php?deslogar=0" class="deslogar" >Deslogar</a>';
	}
	
	echo'<hr>';
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Document</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<div class="mostraNivel">
			<!--<h1>Você é um <?php echo $_SESSION['nivel']; ?></h1>-->
		</div>
		<div class="formCadastro corCadastroAlt">
			<h1 align=center>Alterar</h1>
			<form name="form" action="chamadaLogin.php" method="POST">	
				<input type="hidden" name="idAlt" value="<?= $id ;?>" >	
				Nome  
				<input type="text"  name="nomeAlt"  value="<?= $nome ?>"  ><br><br>																		
				Login  
				<input type="text" name="loginAlt" value="<?= $login ?>" ><br><br>		
				Senha 
				<input type="text"  name="senhaAlt" value="<?= $senha ?>" ><br><br>	
				Nível<br>
				 <select  name="nivelAlt" >
				 	<option value="<?= $nivel ?>"><?= $niveis ?></option> 
				<?php if($id <> $_SESSION['id']){ // impede mudar o próprio nivel ?>
					<option value="0"> Usuário Comum </option> 
					<option value="1"> Administrador </option>
					<?php }  ?>
				<?php if ($_SESSION['nivel']=="Gerente"){ ?> 
					<option value="2"> Gerente </option> 
				<?php } ?>
				</select><br><br><br>
				<input type="submit" class="botao" value="A l t e r a r"/>
				<br><br>
				<a style="margin-left:70px;" href="index.php">Voltar</a>			
			</form>
		</div>
</body>
</html>