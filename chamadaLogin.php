<?php
	@session_start();
	require_once'class.php';
	    if((isset($_POST['login']))AND($_POST['login']<>"")){
			Sessao::login($_POST['login'],$_POST['senha']);
			$_SESSION['mensagem']="Logado com sucesso!";
			header('Location:index.php');
			exit;
		}
		
		if(isset($_GET['deslogar'])){	
			Sessao::logout();
			header('Location:index.php');
			exit;
		}
	
		if(isset($_GET['excluir'])){
	
	// Impede que o Gerente logado excluia a si próprio
			if($_GET['excluir']<>$_SESSION['id']){
				classes::deletando($_GET[excluir]); 
				$_SESSION['mensagem']="Excluído com sucesso!";
				header('Location:index.php');
				exit;
			}
		}
		
		if((isset($_POST['loginCad']))AND ($_POST['loginCad']<>"")){
			classes::cadastrando($_POST['nomeCad'], $_POST['loginCad'], $_POST['senhaCad'], $_POST['nivelCad']);	
			$_SESSION['mensagem']="Cadastrado com sucesso!";
			header('Location:index.php');
			exit;
		}
		
		if((isset($_POST['nomeRec'])) AND ($_POST['nomeRec']<>"")){
			
			if($_POST['senhaRec'] ==  $_POST['repitaSenhaRec']){
				classes::recuperando($_POST['nomeRec'], $_POST['senhaRec']);
				//$_SESSION['mensagem']="Senha recuperada com sucesso!";
				header('Location:index.php');
				exit;	
			}else{
				$_SESSION['mensagem']="Senhas diferentes!";
				header('Location:index.php');
				exit;	
			}
		}
		
		if((isset($_POST['idAlt']))AND ($_POST['nomeAlt']<>"")AND ($_POST['loginAlt']<>"")AND ($_POST['senhaAlt']<>"")){
			classes::alterando($_POST['idAlt'],$_POST['nomeAlt'], $_POST['loginAlt'], $_POST['senhaAlt'], $_POST['nivelAlt']); 
			$_SESSION['mensagem']="Alterado com sucesso!";
			header('Location:index.php');
			exit;
		}else{
			$_SESSION['mensagem']="Não foi possivel alterar!";
			header('Location:index.php');
			exit;
		}
		
	header('Location:index.php');
?>