//$("h2").text($nome)
//<script type="text/javascript" src="javascript/resolucao.js"></script>  

if(!empty($_FILES['arquivo']['tmp_name'])){
    $arquivo = new DomDocument();
    $arquivo.load($_FILES['arquivo']['tmp_name']);
    //var_dump($arquivo);
    
    $linhas = $arquivo.getElementsByTagName("Row");
    //var_dump($linhas);
    
    $primeira_linha = true;
    
    foreach(leitura($linhas) )
    function leitura($linha){
        if($primeira_linha == false){
            $nome = $linha.getElementsByTagName("Data").item(0).nodeValue;
            $email = $linha.getElementsByTagName("Data").item(1).nodeValue;
            
            $niveis_acesso_id = $linha.getElementsByTagName("Data").item(2).nodeValue;
            
            $("h2").text("teste $nome")
            //echo "coluna 1 da tabela ".'"'."$name".'"'."<br>";
            //echo "<h2> insert into($nome,$email,$niveis_acesso_id );</h2>"."<br>";
            
            //Inserir o usuário no BD
            //$result_usuario = "INSERT INTO usuarios (nome, email, niveis_acesso_id) VALUES ('$nome', '$email', '$niveis_acesso_id')";
            //$resultado_usuario = mysqli_query($conn, $result_usuario);
            
            //echo "<hr>";
        }
        $primeira_linha = false;
    }
}