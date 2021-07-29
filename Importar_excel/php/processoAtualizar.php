<?php
	include_once("conexao.php");
	
	$Tabela=$_POST['Tabela'];
	$tables = 'SHOW TABLES';
	$result = mysqli_query($conn,$tables);
	$erro_tabela=1;
	while($row = mysqli_fetch_array($result)){
	  $name = $row['Tables_in_ffb'];
	  //Confere se nome tabela existe
	  if($name == $Tabela){ 
		 // echo 'Tabela '.$name.'<br>'; 
		  $erro_tabela=0;

		if(!empty($_FILES['arquivo']['tmp_name'])){
			$arquivo = new DomDocument();
			$arquivo->load($_FILES['arquivo']['tmp_name']);
			//var_dump($arquivo);
			$linhas = $arquivo->getElementsByTagName('Row');
			//var_dump($linhas);
			$celulas = $arquivo->getElementsByTagName('Cell');
			//var_dump($celulas);
		    $colunas =(count($celulas)/count($linhas));
			
			$query="TRUNCATE TABLE $Tabela"; //Limpeza de Tabela
			$result = mysqli_query($conn,$query);
			if($result){
				//echo 'Tabela Limpa <br>';				
				$searchString = ' ';
				$replaceString = '';	
				$primeira_linha = true;
				$n_inserts=0;
				foreach($linhas as $linha){
					//Inserção das linhas da Tabela
					$tag_linha=$linha->getElementsByTagName('Data');
					if($primeira_linha == false){
						for($i = 0; $i < $colunas; $i++){
							if(is_object($tag_linha->item($i))){
							$aux_var = $tag_linha->item($i)->nodeValue;
							$var[$i] = "'".str_replace("'", $replaceString, $aux_var)."'"; }
							else{
								$var[$i]="' '";
							}
						}
						$valores_coluna=implode(',',$var);
						$total_valores[$n_inserts]=$valores_coluna;
						++$n_inserts;
						
				}else{
					//linha de nome das colunas da Tabela
					for($i = 0; $i < $colunas; $i++){
						$var_tipo[$i] = $tag_linha->item($i)->nodeValue;
					}
					$var_coluna=str_replace($searchString, $replaceString,str_replace(".", $replaceString, implode(',',$var_tipo)));
					//echo $var_coluna;
				}
					$primeira_linha = false;
			}	
			}else{
				echo "Não foi possível Limpar <br>";
					}
					$comando_sql= "INSERT INTO  $Tabela($var_coluna)VALUES(".implode('),(',$total_valores).")";
						/*$insert = mysqli_query($conn,$comando_sql);
						if ($insert) {
							echo "atualização concluida";
							//echo " New record created successfully<hr>";
					  	} else {
							echo "Error: <br>" . mysqli_error($conn);
					  	}*/	
			//echo "atualização concluida";			
		}else{
			echo "erro na leitura da planilha";
		}break;
	}
}
	if($erro_tabela==1){
		echo "Essa tabela '$Tabela' não existe";
	}
	mysqli_close($conn);
	
?>

