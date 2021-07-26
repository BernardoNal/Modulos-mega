<?php
	include_once("conexao.php");
	
	$Tabela=$_POST['Tabela'];
   
		if(!empty($_FILES['arquivo']['tmp_name'])){
			$arquivo = new DomDocument();
			$arquivo->load($_FILES['arquivo']['tmp_name']);
			//var_dump($arquivo);
			$linhas = $arquivo->getElementsByTagName("Row");
			//var_dump($linhas);
			$celulas = $arquivo->getElementsByTagName("Cell");
			//var_dump($celulas);
		    $colunas =(count($celulas)/count($linhas));
			
            $searchString = " ";
            $replaceString = "";

			$primeira_linha = true;
			
			foreach($linhas as $linha){
					
                if($primeira_linha == false){
                    break;
                }else{
                    //recebe nome da colunas da tabela
					for($i = 0; $i < $colunas; $i++){
							$aux = $linha->getElementsByTagName("Data")->item($i)->nodeValue;
                           // $aux2=str_replace("ó", "o", $aux);
                            $aux3=str_replace(".", $replaceString, $aux);
                            $var_tipo[$i] = str_replace($searchString, $replaceString, $aux3); 
					}
					$var_coluna=implode(" varchar(255),",$var_tipo);
                }
				$primeira_linha = false;
			}
            //comando de cria a tabela
            $comando_sql= "CREATE TABLE  $Tabela($var_coluna varchar(255))";
            $insert = mysqli_query($conn,$comando_sql);
            if ($insert) {
                echo "New record created successfully";
              } else {
                echo "Error: <br>" . mysqli_error($conn);
              }
            echo $comando_sql."<br>";

           /* $query = "SELECT * FROM $Tabela";
			$result = mysqli_query($conn,$query);
		    if($result){
			while($row = mysqli_fetch_array($result)){
				$name = $row["nome"];
				echo "Name: ".$name."<br>";
			}
		    }else{
			echo "n funcionou select 2 <br>";
		    }*/
		}else{
			echo "erro na leitura da planilha";
		}
	
	
	mysqli_close($conn);
?>

