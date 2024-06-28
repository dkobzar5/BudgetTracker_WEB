<?php
include $_SERVER['DOCUMENT_ROOT'] . '/src/helpers.php';

$pdo = getPDO();
$newBalance = $_POST['newBalance'];
$accountId = $_POST['accountId'];

$stmt = $pdo->prepare("UPDATE accounts SET balance = :balance WHERE account_id = :account_id");
$stmt->bindParam(':balance', $newBalance, PDO::PARAM_STR);
$stmt->bindParam(':account_id', $accountId, PDO::PARAM_INT);
$stmt->execute();

echo json_encode(array('success' => true, 'message' => 'Account balance successfully updated'));
?>