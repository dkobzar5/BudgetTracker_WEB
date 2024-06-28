<?php
session_start();

require_once __DIR__ . '/../helpers.php';

$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirmation = $_POST['password_confirmation'];

$_SESSION['validation'] = [];

validationAttr($name, "name");
validationAttr($surname, "surname");
validationAttr($email, "email");
validationPassword($password);
validationPasswordMatch($password, $password_confirmation);

if ((count($_SESSION['validation']) >= 2) || !empty($_SESSION['validation']['password'])) {
	redirect('/singup.php');
}

$pdo = getPDO();
$queryInsertUser = "INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)";
$params = [
    'name' => $name,
    'surname' => $surname,
    'email' => $email,
    'password' => password_hash($password, PASSWORD_DEFAULT)
];

$pdo->beginTransaction();

try {
    $stmtInsertUser = $pdo->prepare($queryInsertUser);
    $stmtInsertUser->execute($params);
    
    $user_id = $pdo->lastInsertId();
    
    $queryInsertAccount = "INSERT INTO accounts (user_id, account_name, balance, currency) VALUES (:user_id, :account_name, :balance, :currency)";
    $paramsAccount = [
        'user_id' => $user_id,
        'account_name' => 'Basic account', 
        'balance' => 0, 
        'currency' => 'zł' 
    ];
    
    $stmtInsertAccount = $pdo->prepare($queryInsertAccount);
    $stmtInsertAccount->execute($paramsAccount);
    
    $pdo->commit();
    
    redirect('/login.php');
} catch (Exception $e) {
    $pdo->rollBack();
    die($e->getMessage());
}

?>