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

    <form class="card" action="src/actions/loginAction.php" method="post" enctype="multipart/form-data">
        <h2>Log in</h2>


        <label for="email">
            E-mail
            <input type="email" id="email" name="email" placeholder="adam@gmail.com" value="<?= getOldValue('email') ?>"
                <?= hasError('email') ?>>
            <?= getErrorMessage('email') ?>
        </label>

        <label for="password">
            Password
            <input type="password" id="password" name="password" placeholder="******" <?= hasError('password') ?>>
            <?= getPasswordMessageErrors() ?>
        </label>
        <?php if (hasMessage('error')): ?>
            <span class='validation_error'><?php echo getMessage('error') ?></span>
        <?php endif; ?>
        <button type="submit" id="submit">Sing up</button>
    </form>
    <?php clearSession(); ?>
</body>

</html>