<?php

$accdir = "C:\\PROJETO\\projeto top\\Release XWYD\\DBSRV\\run\\account\\"; // Diretório da pasta "account" do servidor
$userid = trim($_POST['userid']);
$password = trim($_POST['password']);
$initial = strtoupper(substr($userid, 0, 1)); // Maiúscula por padrão
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
    echo implode("<br />", $errors);
    exit();
}

// Verifica se o diretório inicial existe, se não, cria-o
$initialDir = $accdir . $initial;
if (!file_exists($initialDir)) {
    mkdir($initialDir, 0777, true);
}

// Verifica se a conta já existe
if (file_exists($initialDir . "\\" . $userid)) {
    echo "Conta já existe";
} else {
    // Tenta abrir o arquivo "5900xt" para leitura
    $fileToRead = "5900xt";
    if (!file_exists($fileToRead)) {
        die("Arquivo de modelo não encontrado");
    }
    
    $f = @fopen($fileToRead, "r") or die("Falha ao abrir o arquivo");
    $acc = @fread($f, filesize($fileToRead));
    
    // Substitui o userid e a senha de exemplo pelo fornecido
    $demoid = substr($acc, 0, strlen($userid));
    $demopass = substr($acc, 16, strlen($password));
    $acc = str_replace($demoid, $userid, $acc);
    $acc = str_replace($demopass, $password, $acc);
    
    // Cria o novo arquivo de conta
    $f2 = @fopen($initialDir . "\\" . $userid, "w");
    if ($f2 === false) {
        die("Falha ao criar o arquivo de conta");
    }
    @fwrite($f2, $acc) or die("Falha ao escrever no arquivo");
    @fclose($f2);
    @fclose($f);
    
    echo "Conta Criada Com Sucesso";
}

?>
