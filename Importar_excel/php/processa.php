<?php
	include_once("conexao.php");
	
	$Tabela=$_POST['Tabela'];
   
	//$dados=$_FILES['arquivo']['error'];
	//echo $dados;
	//var_dump($dados);	
	
		if(!empty($_FILES['arquivo']['tmp_name'])){
			$arquivo = new DomDocument();
			$arquivo->load($_FILES['arquivo']['tmp_name']);
			//var_dump($arquivo);
			//echo "<hr>";
			$linhas = $arquivo->getElementsByTagName("Row");
			//var_dump($linhas);
			$celulas = $arquivo->getElementsByTagName("Cell");
			//var_dump($celulas);
            
		    $colunas =(count($celulas)/count($linhas));
			
			$query = "SELECT * FROM usuario";
			$result = mysqli_query($conn,$query);
			
		
		if($result){
			while($row = mysqli_fetch_array($result)){
				$name = $row["nome"];
				echo "Name: ".$name."<br>";
			}
		}
		else{
			echo "n funcionou select 1 <br>";
		}
			

			$primeira_linha = true;
			
			
			foreach($linhas as $linha){
				
				if($primeira_linha == false){
					echo "<hr>";
	
					for($i = 0; $i < $colunas; $i++){
						
						
							$var[$i] = "'".$linha->getElementsByTagName("Data")->item($i)->nodeValue."'";
					    	
					}
					$valores_coluna=implode(",",$var);
					$comando_sql= "INSERT INTO  $Tabela($var_coluna)VALUES($valores_coluna)";
					$insert = mysqli_query($conn,$comando_sql);
					if ($insert) {
						echo "New record created successfully";
					  } else {
						echo "Error: " . $sql . "<br>" . mysqli_error($conn);
					  }
					echo $comando_sql."<br>";

				}
				else{
					
					for($i = 0; $i < $colunas; $i++){
							$var_tipo[$i] = $linha->getElementsByTagName("Data")->item($i)->nodeValue;
					}
					$var_coluna=implode(",",$var_tipo);
				}
				$primeira_linha = false;
			}
			$result = mysqli_query($conn,$query);
			
		
		if($result){
			while($row = mysqli_fetch_array($result)){
				$name = $row["nome"];
				echo "Name: ".$name."<br>";
			}
		}
		else{
			echo "n funcionou select 2 <br>";
		}
		}else{
			echo "erro na leitura da planilha";
		}
	
	
	mysqli_close($conn);
?>

