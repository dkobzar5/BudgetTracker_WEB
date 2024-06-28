<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedTerm = $_POST['term'] ?? null;

    if ($selectedTerm) {
        $_SESSION['selected_term'] = $selectedTerm;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Term not provided']);
    }
}
?>