<?php

$accdir = "C:\\PROJETO\\projeto top\\Release XWYD\\DBSRV\\run\\account\\"; // Diretório da pasta "account" do servidor
$userid = trim($_POST['userid']);
$password = trim($_POST['password']);
$initial = substr($userid, 0, 1);
$userlenght = strlen($userid);
$passlenght = strlen($password);

$errors = [];

// Verifica se o usuário é válido
if (!preg_match("/^[0-9a-zA-Z]{4,12}$/", $userid)) {
    $errors[] = "Use letras de A a Z, com 4 a 12 caracteres para o usuário.";
}

// Verifica se a senha é válida
if (!preg_match("/^[0-9a-zA-Z]{4,12}$/", $password)) {
    $errors[] = "Use letras ou números, com 4 a 12 caracteres para a senha.";
}

// Exibe erros se houver algum
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . "<br />";
    }
    exit();
}

// Verifica se o caractere inicial é uma letra
if (preg_match("/^[a-zA-Z]$/", $initial)) {
    $initial = strtoupper($initial);
} else {
    $initial = "etc";
}

// Verifica se a conta já existe
if (file_exists($accdir . "\\" . $initial . "\\" . $userid)) {
    echo "Conta já existe";
} else {
    // Tenta abrir o arquivo "5900xt" para leitura
    $f = @fopen("5900xt", "r") or die("Falha ao abrir o arquivo");
    $acc = @fread($f, 9999);
    
    // Substitui o userid e a senha de exemplo pelo fornecido
    $demoid = substr($acc, 0, $userlenght);
    $demopass = substr($acc, 16, $passlenght);
    $acc = str_replace($demoid, $userid, $acc);
    $acc = str_replace($demopass, $password, $acc);
    
    // Cria o novo arquivo de conta
    $f2 = @fopen($accdir . "\\" . $initial . "\\" . $userid, "a");
    @fwrite($f2, $acc) or die("Falha ao escrever no arquivo");
    @fclose($f2);
    @fclose($f);
    
    echo "Conta Criada Com Sucesso";
    exit();
}

?>
