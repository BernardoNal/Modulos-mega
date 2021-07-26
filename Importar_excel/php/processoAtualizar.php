<?php
	include_once("conexao.php");
	
	$Tabela=$_POST['Tabela'];
	$tables = "SHOW TABLES";
	$result = mysqli_query($conn,$tables);
	$erro_tabela=1;
	while($row = mysqli_fetch_array($result)){
	  $name = $row["Tables_in_ffb"];
	  //Confere se nome tabela existe
	  if($name == $Tabela){ 
		  echo "Tabela ".$name."<br>"; 
		  $erro_tabela=0;

		if(!empty($_FILES['arquivo']['tmp_name'])){
			$arquivo = new DomDocument();
			$arquivo->load($_FILES['arquivo']['tmp_name']);
			//var_dump($arquivo);
			$linhas = $arquivo->getElementsByTagName("Row");
			//var_dump($linhas);
			$celulas = $arquivo->getElementsByTagName("Cell");
			//var_dump($celulas);
		    $colunas =(count($celulas)/count($linhas));
			
			$query="TRUNCATE TABLE $Tabela"; //Limpeza de Tabela
			$result = mysqli_query($conn,$query);
			if($result){
				echo "Tabela Limpa <br>";				
				$searchString = " ";
				$replaceString = "";	
				$primeira_linha = true;
			
				foreach($linhas as $linha){
					//Inserção das linhas da Tabela
					if($primeira_linha == false){
						//echo "<hr>";
						for($i = 0; $i < $colunas; $i++){
							$aux_var = $linha->getElementsByTagName("Data")->item($i)->nodeValue;
							$var[$i] = "'".str_replace("'", $replaceString, $aux_var)."'"; 
						}
						$valores_coluna=implode(",",$var);
						//comando de inserção
						$comando_sql= "INSERT INTO  $Tabela($var_coluna)VALUES($valores_coluna)";
						$insert = mysqli_query($conn,$comando_sql);
						if ($insert) {
							//echo " New record created successfully<hr>";
					  	} else {
							echo "Error: <br>" . mysqli_error($conn);
					  	}
						//echo $comando_sql."<br>";
				}else{
					//linha de nome das colunas da Tabela
					for($i = 0; $i < $colunas; $i++){
						$aux = $linha->getElementsByTagName("Data")->item($i)->nodeValue;
						// $aux2=str_replace("ó", "o", $aux);
						 $aux3=str_replace(".", $replaceString, $aux);
						 $var_tipo[$i] = str_replace($searchString, $replaceString, $aux3); 
					}
					$var_coluna=implode(",",$var_tipo);
				}
					$primeira_linha = false;
			}	
			}else{
				echo "Não foi possível Limpar <br>";
					}	
			echo "atualização concluida";			
		}else{
			echo "erro na leitura da planilha";
		}
	}}
	if($erro_tabela==1){
		echo "Essa tabela '$Tabela' não existe";
	}
	mysqli_close($conn);
	
?>

