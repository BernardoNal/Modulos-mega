<?php
	include_once("conexao.php");
	
	$Tabela=$_POST['Tabela'];
		if(!empty($_FILES['arquivo']['tmp_name'])){
			$arquivo = new DomDocument();
			$arquivo->load($_FILES['arquivo']['tmp_name']);
			$linhas = $arquivo->getElementsByTagName('Row');
			$celulas = $arquivo->getElementsByTagName('Cell');
		    $colunas =(count($celulas)/count($linhas));
			
			$query="TRUNCATE TABLE $Tabela"; //Limpeza de Tabela
			$result = mysqli_query($conn,$query);
			if($result){			
				$searchString = ' ';
				$replaceString = '';	
				$primeira_linha = true;
				$n_inserts=0;
				foreach($linhas as $linha){
					
					$tag_linha=$linha->getElementsByTagName('Data');
					if($primeira_linha == false){
						//Recebendo linhas a serem inseridas
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
					//recebe nome da colunas da tabela
					for($i = 0; $i < $colunas; $i++){
						$var_tipo[$i] = $tag_linha->item($i)->nodeValue;
					}
					//tratamento do nome das colunas
					$var_coluna=str_replace($searchString, $replaceString,str_replace(".", $replaceString, implode(',',$var_tipo)));
					
				}
					$primeira_linha = false;
			}
			//comando de insere todos linhas da tabela
				$comando_sql= "INSERT INTO  $Tabela($var_coluna)VALUES(".implode('),(',$total_valores).")";
				$insert = mysqli_query($conn,$comando_sql);
				if ($insert) {
					echo "Atualização da tabela concluída";
				} else {
					echo "Error: " . mysqli_error($conn)."<br>";
					  	}	
			}else{
				echo "Error: " . mysqli_error($conn)."<br>";
				
					}
						
		}else{
			echo "erro na leitura da planilha";
		}
	mysqli_close($conn);
?>