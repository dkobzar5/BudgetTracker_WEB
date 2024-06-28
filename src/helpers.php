<?php
session_start();
require_once __DIR__ . "/config.php";
require_once __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;

function redirect(string $path)
{
    header("Location: $path");
    die();
}
function validationAttr($fild_value, $fild_name)
{
    $patterns = array(
        "name" => "/^[a-zA-Z\s'-]+$/",
        "surname" => "/^[a-zA-Z\s'-]+$/",
        "email" => "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
    );
    $_SESSION['old'][$fild_name] = $fild_value;
    if (empty($fild_value)) {
        $_SESSION['validation'][$fild_name] = capitalize($fild_name) . ' is empty';
    } elseif (!preg_match($patterns[$fild_name], $fild_value)) {
        $_SESSION['validation'][$fild_name] = capitalize($fild_name) . ' is incorrect';
    }
}
function hasError($fild_name)
{
    if (isset($_SESSION['validation'][$fild_name])) {
        $value = $_SESSION['validation'][$fild_name];
        return isset($value) && !empty($value) ? "aria-invalid='true'" : "";
    }

}
function capitalize($str)
{
    return ucwords(strtolower($str));
}
function getErrorMessage($fild_name)
{
    if (isset($_SESSION['validation'][$fild_name])) {
        return "<span class='validation_error'><small>" . $_SESSION['validation'][$fild_name] . "</small></span>";
    }
    return "";
}
function clearSession()
{
    $_SESSION['validation'] = [];
}
function addOldValue($key, $value)
{
    $_SESSION['old'][$key] = $value;
}
function getOldValue($key)
{
    $value = $_SESSION['old'][$key] ?? "";
    unset($_SESSION['old'][$key]);
    return $value;
}
function validationPassword($fild_value)
{
    $_SESSION['validation']["password"] = [];
    if (empty($fild_value)) {
        array_push($_SESSION['validation']["password"], "Password is empty");
        return;
    }
    if (!preg_match("/(?=.*[A-Za-z])/", $fild_value)) {
        array_push($_SESSION['validation']["password"], "At least one letter in any case");
    }
    if (!preg_match("/(?=.*\d)/", $fild_value)) {
        array_push($_SESSION['validation']["password"], "At least one digit");
    }
    if (!preg_match("/(?=.*[^\da-zA-Z])/", $fild_value)) {
        array_push($_SESSION['validation']["password"], "At least one special character");
    }
    if (!preg_match("/.{8,}/", $fild_value)) {
        array_push($_SESSION['validation']["password"], "Password length is at least 8 characters ");
    }
}
function getPasswordMessageErrors()
{
    if (isset($_SESSION['validation']['password'])) {
        foreach ($_SESSION['validation']['password'] as $error) {
            echo "<span class='validation_error'><small>&#x2717; " . $error . "</small></span>";
        }
    }
}
function validationPasswordMatch($password, $password_confirmation)
{
    if ($password != $password_confirmation) {
        $_SESSION['validation']['password_confirmation'] = "Passwords do not match";
    }
}

function getPDO()
{
    try {
        return new PDO('mysql:host=' . DB_HOST . ';charset=utf8;port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}


function setMessage(string $key, string $message)
{
    $_SESSION['message'][$key] = $message;
}

function hasMessage(string $key)
{
    return isset($_SESSION['message'][$key]);
}

function getMessage(string $key)
{
    $message = $_SESSION['message'][$key] ?? '';
    unset($_SESSION['message'][$key]);
    return $message;
}
function findUser($email)
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function getUser($id)
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}


function logout()
{
    unset($_SESSION['jwt']);
    redirect('/');
}

function checkAuth()
{
    if (!isset($_SESSION['jwt'])) {
        redirect('/login.php');
    }
}

function checkGuest()
{
    if (isset($_SESSION['jwt'])) {
        redirect('/home.php');
    }
}

function verifyToken()
{
    $jwt = isset($_SESSION['jwt']) ? $_SESSION['jwt'] : null;

    if ($jwt) {
        $jwt = str_replace('Bearer ', '', $jwt);

        try {
            $decoded = JWT::decode($jwt, JWT_KEY, array('HS256'));
            return $decoded;


        } catch (Exception $e) {
            return null;
        }
    } else {
        return null;
    }
}

function getUserAccount($userId)
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT *
            FROM accounts
            JOIN users ON accounts.user_id = users.id
            WHERE users.id = :user_id");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getUserExpenses($accountId, $term)
{
    $pdo = getPDO();
    $dateCondition = '';
    switch ($term) {
        case 'day':
            $dateCondition = "AND date >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            break;
        case 'week':
            $dateCondition = "AND date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
            break;
        case 'month':
            $dateCondition = "AND date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            break;
        case 'year':
            $dateCondition = "AND date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
            break;
        default:
            $dateCondition = "";
            break;
    }

    $query = "SELECT SUM(amount) as amount, category_name, category_color
              FROM expenses
              INNER JOIN categories ON category_id = categories.categories
              WHERE account_id = :account_id $dateCondition
              GROUP BY category_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':account_id', $accountId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function hexToRgba($hex, $opacity = 1)
{

    $hex = str_replace('#', '', $hex);

    $length = strlen($hex);
    if ($length == 6) {
        $rgb['r'] = hexdec(substr($hex, 0, 2));
        $rgb['g'] = hexdec(substr($hex, 2, 2));
        $rgb['b'] = hexdec(substr($hex, 4, 2));
    } elseif ($length == 8) {
        $rgb['r'] = hexdec(substr($hex, 0, 2));
        $rgb['g'] = hexdec(substr($hex, 2, 2));
        $rgb['b'] = hexdec(substr($hex, 4, 2));
        $opacity = round((hexdec(substr($hex, 6, 2)) / 255), 2);
    } else {
        return false;
    }

    return 'rgba(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ',' . $opacity . ')';
}
function getCategories()
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT *
            FROM categories");

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getTerm()
{
    $term = $_SESSION['selected_term'];
    return $term;
}