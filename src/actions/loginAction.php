<?php
    session_start();
    require_once __DIR__ . '/../helpers.php';
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../vendor/autoload.php'; 

    use \Firebase\JWT\JWT;

    checkGuest();
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $_SESSION['validation'] = [];

    validationAttr($email, "email");

    if (count($_SESSION['validation']) >= 1) {
        redirect('/login.php');
    }

    $user = findUser($email);

    if(!$user){
        setMessage('error', 'User not found');
        addOldValue('email', $email);
        redirect('/login.php');
    }
    if(!password_verify($password, $user['password'])){
        setMessage('error', 'Incorrect password');
        redirect('/login.php');
    }

    $payload = array(
        "user_id" => $user['id'],
    );
    $jwt = JWT::encode($payload, JWT_KEY);

    
    $_SESSION['jwt'] = $jwt;

    redirect('/home.php');
?>
