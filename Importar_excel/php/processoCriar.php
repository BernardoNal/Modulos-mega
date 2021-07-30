<?php
	include_once("conexao.php");
	
	$Tabela=$_POST['Tabela'];
		if(!empty($_FILES['arquivo']['tmp_name'])){
			$arquivo = new DomDocument();
			$arquivo->load($_FILES['arquivo']['tmp_name']);
			$linhas = $arquivo->getElementsByTagName('Row');
			$celulas = $arquivo->getElementsByTagName('Cell');
		    $colunas =(count($celulas)/count($linhas));
			
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
							$aux = $tag_linha->item($i)->nodeValue;
                            $aux2=str_replace(".", $replaceString, $aux);
                            $var_tipo[$i] = str_replace($searchString, $replaceString, $aux2); 
					}
					//tratamento do nome das colunas
					$var_coluna=implode(",",$var_tipo);
					$var_tipo_coluna=implode(" varchar(255),",$var_tipo);
					
					//comando de cria a tabela
					$comando_sql= "CREATE TABLE  $Tabela( id int NOT NULL primary key AUTO_INCREMENT,$var_tipo_coluna varchar(255))";
					$insert = mysqli_query($conn,$comando_sql);
					if ($insert) {
						//echo "New record created successfully";
					  } else {
						echo "Error: <br>" . mysqli_error($conn);
						break;
					  }
					
                }
				$primeira_linha = false;
			}
            //comando de insere todos linhas da tabela
				$comando_sql= "INSERT INTO  $Tabela($var_coluna)VALUES(".implode('),(',$total_valores).")";
				$insert = mysqli_query($conn,$comando_sql);
				if ($insert) {
					echo "Criação da tabela concluída";
				} else {
					echo "Error: " . mysqli_error($conn)."<br>";
					  	}	
		}else{
			echo "erro na leitura da planilha";
		}
	mysqli_close($conn);
?>