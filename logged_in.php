<?php include('connect.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Magebit homework</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="style.php"/>
</head>
<body>
    <div class="wrapper">
        <?php if (isset($_SESSION['success'])): ?>
            <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['name'])): ?>
            <p>Welcome, <?php echo $_SESSION['name']; ?>!</p>
            <a href="logged_in.php?logout='1'" class="logout">SIGN OUT</a>
            <a href="" class="logout">MY ACCOUNT</a>

        <?php endif; ?>

    </div>
</body>
</html>