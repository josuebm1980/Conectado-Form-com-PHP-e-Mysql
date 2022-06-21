<?php
/* Declarando variáveis ​​e verificando se estão vazias ou não*/
/*Na linha 4, verificamos se ( $username, $password, $gender, $email, $phoneCode and $phone) está definido ou não. Se eles não estiverem definidos, vamos pular até linha 57. Se estiverem definidos, o código da linha 9 será executado.*/
if (isset($_POST['submit'])) {
    if (isset($_POST['username']) && isset($_POST['password']) &&
        isset($_POST['gender']) && isset($_POST['email']) &&
        isset($_POST['phoneCode']) && isset($_POST['phone'])) {
        

        /* Usando um código da linha 11 a 16, estamos pegando dados do formulário HTML e armazenando-os em variáveis ​​PHP*/
        $username = $_POST['username'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $phoneCode = $_POST['phoneCode'];
        $phone = $_POST['phone'];

        /* Fazendo uma conexão com o banco de dados atribuindo valores para variáveis*/
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "test";

        /*Então, na linha 25, fazemos uma conexão com o banco de dados e a atribuímos a um a variável $conn */
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        
        /* Depois disso, na linha 28 verificamos se nosso banco de dados está conectado. Se não houver conexão, exibimos o erro e saímos. Se um banco de dados estiver conectado, continuamos da linha 34. */
        if ($conn->connect_error) {
            die('Could not connect to the database.');
        }

        /* Declare consulta SQL em uma variável
         Na linha 35, há uma consulta SELECT para selecionar dados de um banco de dados e na linha 36, há uma consulta INSERT para inserir o registro do usuário em um banco de dados. */
        else { 
            $Select = "SELECT email FROM register WHERE email = ? LIMIT 1";
            $Insert = "INSERT INTO register(username, password, gender, email, phoneCode, phone) values(?, ?, ?, ?, ?, ?)";

        /* Usando a declaração prepared statement 
        Depois disso, estamos usando a instrução prepared statement para executar nossa consulta e proteger nosso banco de dados contra injeção de SQL.*/

        /* Executamos a consulta SELECT
            Na linha 43 estamos passando um valor de email para uma consulta SELECT e definimos “s” porque email é uma string. 
            Em uma consulta SELECT, estamos selecionando apenas e-mail, então, na linha 47, capturamos esse valor de e-mail. */
            $stmt = $conn->prepare($Select);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($resultEmail);
            $stmt->store_result();
            $stmt->fetch();
            $rnum = $stmt->num_rows; /* Na linha 50, armazenamos o número de linhas em uma variável  $rnum do nosso resultado.*/
            
        /* Insira os dados do usuário
            Então, na linha 54, ​​verificamos se nosso resultado contém 0 linhas, se sim, continuamos executando nossa codificação, caso contrário, pulamos para a linha 67 e damos uma mensagem “Alguém já se registra usando este email”. */ 
            if ($rnum == 0) {
                $stmt->close();

                $stmt = $conn->prepare($Insert);
                $stmt->bind_param("ssssii",$username, $password, $gender, $email, $phoneCode, $phone);
                if ($stmt->execute()) {
                    echo "New record inserted sucessfully.";
                }
                else {
                    echo $stmt->error;
                }
            }
            else {
                echo "Someone already registers using this email.";
            }
            $stmt->close();
            $conn->close();
        }
    }
    else {
        echo "All field are required.";
        die();
    }
}
else {
    echo "Submit button is not set";
}
?>