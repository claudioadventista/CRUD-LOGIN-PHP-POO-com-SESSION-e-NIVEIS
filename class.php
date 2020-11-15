<?php 
date_default_timezone_set('America/Sao_Paulo');

	// ***************    Classes conexão   **************

abstract class BancoDados{
		const host = 'localhost';
		const bdCliente = 'cliente';	
		const user = 'root';
		const password = '';
	
		// Conecta a todos os bancos locais
		static function coneccaoUNICA(){	
			try{		
				$pdoUNI = new PDO("mysql:host=".self::host.";
				charset=utf8", self::user, self::password);			
				$pdoUNI->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
				return $pdoUNI;	
			} 
			catch(PDOException $e) {	
				echo 'ERROR: ' . $e->getMessage();		
			}	
	}
	
		static function conectarW(){
			try{			
				$pdoW = new PDO("mysql:host=".self::host.";
				dbname=".self::bdCliente.";
				charset=utf8", self::user, self::password);			
				$pdoW->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);					
				return $pdoW;		
			} 
			catch(PDOException $e) {	
				echo 'ERROR: ' . $e->getMessage();		
			}	
		}
}

// ***************    Classe sessão login    **************
 
abstract class Sessao{
	function __construct(){	
	}
	static function estaLogado(){
		if(!isset($_SESSION)) {	
			session_start();
		}
		if (!isset($_SESSION['nivel'])){			
			Sessao::logout();			
			return false;		
		} else {			
			return true;	
		}	
	}
			
	static function logout(){		
		if(!isset($_SESSION)) {			
			session_start();		
		}		
			$_SESSION['logado'] = NULL;
			$_SESSION['nivel'] = NULL;
			$_SESSION['id'] = NULL;
			$_SESSION['nome'] = NULL;
			$_SESSION['senha'] = NULL;			
			unset ($_SESSION['nivel']);		
			unset ($_SESSION['logado']);
			unset($_SESSION['id']);
			unset($_SESSION['nome']);
			unset($_SESSION['nome']);		
			session_destroy();
		}
		
		static function login($login,$senha){	
			$pdo = BancoDados::coneccaoUNICA();		
			$handler = $pdo->prepare('SELECT cliente.aluno.* FROM cliente.aluno WHERE login =:pLogin AND senha =:pSenha');
			$handler->bindValue(':pLogin',$login);		
			$handler->bindValue(':pSenha',$senha);	
			$handler->execute();	
			$usuario = $handler->fetch(PDO::FETCH_OBJ);		
			$logado = $handler->rowCount();
			
		if ($logado){		
			session_start();
		$nivel = $usuario->nivel;
		if ($nivel == 0){	
			$nivel = "Usuário comum";
		}else if($nivel == 1){	
		    $nivel = "Administrador";
		}else{
			$nivel = "Gerente";
		}
			$_SESSION['id'] = $usuario->id;		
			$_SESSION['nome'] = $usuario->nome;
			$_SESSION['logado'] = $usuario->login;
			$_SESSION['senha'] = $usuario->senha;
			$_SESSION['nivel'] = $nivel;		
		return true;
		} else {
		Sessao::logout();		
		return false;					
		}		
	}		
}

// ***************    Classes do CRUD    **************

abstract class classes{
	static function lista(){
		try {  
			$pdo = BancoDados::coneccaoUNICA();
	       // No SELECT deve ser indicado o banco, a tabela e a coluna
			$handler = $pdo->prepare('SELECT cliente.aluno.* FROM cliente.aluno ORDER BY cliente.aluno.id DESC');		
			$handler->execute();		
			$usuario = $handler->fetchAll(PDO::FETCH_OBJ);		
			return $usuario; 	
		}
		catch (PDOException $e) {          
			echo "Error: ".$e->getMessage();        
			}
		}	

	// Conta o numero de gerentes do banco
	static function contagem(){
		try {  
			$pdo = BancoDados::coneccaoUNICA();
			$conta = $pdo->prepare('SELECT  cliente.aluno.* FROM cliente.aluno WHERE cliente.aluno.nivel = 2');	
			$conta->execute();	
			$logado = $conta->rowCount();
			return $logado; 	
		}
		catch (PDOException $e) {          
			echo "Error: ".$e->getMessage();        	
		}
  	}	

	static function cadastrandoGerente(){	
		try {
			$pdo = BancoDados::coneccaoUNICA();		
			$cadastroG = $pdo->prepare("INSERT INTO cliente.aluno (nome,login,senha,nivel) VALUES ('admin', 'admin', 'admin', 2)");		
			$cadastroG->execute();
		return $cadastroG;		
		} 
			catch (PDOException $e) 
		{		
			echo "Error: ".$e->getMessage();		
		}
	}

	static function listandoUnico($idUni){
		try {  
			$pdo = BancoDados::coneccaoUNICA();
			// No SELECT deve ser indicado o banco, a tabela e a coluna
			$listaUni = $pdo->prepare('SELECT cliente.aluno.* FROM cliente.aluno WHERE cliente.aluno.id =:pIdUni');		
			$listaUni->bindValue(':pIdUni',$idUni);		
			$listaUni->execute();		
			$user = $listaUni->fetchAll(PDO::FETCH_OBJ);		
			return $user; 	
		}
		catch (PDOException $e) {          
			echo "Error: ".$e->getMessage();        
			}
		}	

	static function cadastrando($nomeCad,$loginCad,$senhaCad,$nivelCad){	
		try {
			$pdo = BancoDados::coneccaoUNICA();		
			$cadastro = $pdo->prepare("INSERT INTO cliente.aluno (nome,login,senha,nivel) VALUES (:pNomeCad,:pLoginCad,:pSenhaCad,:pNivelCad)");		
			$cadastro->bindValue(':pNomeCad',$nomeCad);			
			$cadastro->bindValue(':pLoginCad',$loginCad);
			$cadastro->bindValue(':pSenhaCad',$senhaCad);
			$cadastro->bindValue(':pNivelCad',$nivelCad);
			$cadastro->execute();
		return $cadastro;	
		} 
			catch (PDOException $e) 
		{		
			echo "Error: ".$e->getMessage();		
		}	
	}

	static function recuperando($nomeRec,$senhaRec){		
		try {
			$pdo = BancoDados::conectarW();	
			$conta = $pdo->prepare('SELECT  * FROM aluno WHERE nome =:pNome');	
			$conta->bindValue(':pNome',$nomeRec);	
			$conta->execute();	
			$logado = $conta->rowCount();
			if($logado==0){
				$_SESSION['mensagem']="Nome não foi encontrado!";
			}else{
			
				$recupera = $pdo->prepare("UPDATE aluno SET senha =:pSenhaRec WHERE nome =:pNomeRec");			
				$recupera->bindValue(':pNomeRec',$nomeRec);			
				$recupera->bindValue(':pSenhaRec',$senhaRec);
				$recupera->execute();
				$_SESSION['mensagem']="Senha recuperada com sucesso!";
			return $recupera;	
			}
		} 
			catch (PDOException $e) 		
		{		
			echo "Error: ".$e->getMessage();
			exit;		
		}
	}

	static function alterando($idAlt,$nomeAlt,$loginAlt,$senhaAlt,$nivelAlt){
		try {
			$pdo = BancoDados::conectarW();	
			$altera = $pdo->prepare("UPDATE aluno SET nome =:nome, login =:login, senha =:senha, nivel =:nivel WHERE id =:id ");			
			$altera->bindValue(':id',$idAlt);			
			$altera->bindValue(':nome',$nomeAlt);
			$altera->bindValue(':login',$loginAlt);
			$altera->bindValue(':senha',$senhaAlt);
			$altera->bindValue(':nivel',$nivelAlt);
			$altera->execute();
		return $altera;		
		} 
			catch (PDOException $e) 
		{		
			echo "Error: ".$e->getMessage();
			exit;		
		}
	}

	static function deletando($idDel){	
		try {
			$pdo = BancoDados::conectarW();	
			$delete = $pdo->prepare("DELETE FROM aluno WHERE id =:pIdDel");					
			$delete->bindValue(':pIdDel',$idDel);
			$delete->execute();
		return $recupera;		
		} 
			catch (PDOException $e) 
		{		
			echo "Error: ".$e->getMessage();		
		}
	}
}

?>