<?php 
require_once __DIR__ . '/src/helpers.php';
checkGuest();
?>
<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="/css/form.css">
<title>Main</title>
</head>
<html lang="en" data-theme="light">
<body>

<form class="card" action="src/actions/registerAction.php" method="post" enctype="multipart/form-data">
    <h2>Sing up</h2>

    <label for="name">
        Name
        <input
            type="text"
            id="name"
            name="name"
            placeholder="Name"
            value="<?= getOldValue('name')?>"
            <?= hasError('name')?>
        >
        <?= getErrorMessage('name')?>
    </label>

    <label for="surname">
        Surname
        <input
            type="text"
            id="surname"
            name="surname"
            placeholder="Surname"
            value="<?= getOldValue('surname')?>"
            <?= hasError('surname')?>
        >
        <?= getErrorMessage('surname')?>
    </label>

    <label for="email">
        E-mail
        <input
            type="email"
            id="email"
            name="email"
            placeholder="adam@gmail.com"
            value="<?= getOldValue('email')?>"
            <?= hasError('email')?>
        >
        <?= getErrorMessage('email')?>
    </label>

    <div class="grid">
        <label for="password">
            Password
            <input
                type="password"
                id="password"
                name="password"
                placeholder="******"
                <?= hasError('password')?>
            >
            <?= getPasswordMessageErrors()?>
        </label>

        <label for="password_confirmation">
            Confirm
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="******"
                <?= hasError('password_confirmation')?>
            >
            <?= getErrorMessage('password_confirmation')?>
        </label>
    </div>

        <label for="terms">
            <input
                type="checkbox"
                id="terms"
                name="terms"
                required
            >
             I accept all terms of use
        </label>

    <button
        type="submit"
        id="submit"
    >Sing up</button>
</form>
<?php clearSession();?>
</body>
</html>