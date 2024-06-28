<?php
require_once __DIR__ . '/../helpers.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $user_id = $_POST['user_id'];
    $account_id = $_POST['account_id'];

    $currentDate = new DateTime();
    $formattedDate = $currentDate->format('Y-m-d');

    $pdo = getPDO();
    $pdo->beginTransaction();

    try {
        $queryInsertExpense = "INSERT INTO expenses (user_id, account_id, category_id, amount, description, date)
                               VALUES (:user_id, :account_id, :category_id, :amount, :description, :date)";
        $stmtInsertExpense = $pdo->prepare($queryInsertExpense);
        $stmtInsertExpense->execute([
            'user_id' => $user_id,
            'account_id' => $account_id,
            'category_id' => $category,
            'amount' => $amount,
            'description' => $description,
            'date' => $formattedDate
        ]);
        $queryUpdateBalance = "UPDATE accounts SET balance = balance - :amount WHERE account_id = :account_id";
        $stmtUpdateBalance = $pdo->prepare($queryUpdateBalance);
        $stmtUpdateBalance->execute([
            'amount' => $amount,
            'account_id' => $account_id
        ]);

        $pdo->commit();

        redirect('/home.php');
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error inserting expense: " . $e->getMessage());
    }
}

?>