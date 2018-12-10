<?php include('connect.php'); ?>
<?php if (!isset($_SESSION['name'])) {
    header('location: main');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Magebit homework</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css"/>
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
            <nav class="nav">
                <a href="account" class="nav__link">MY ACCOUNT</a>
                <a href="logged_in.php?logout='1'" class="nav__link">SIGN OUT</a>
            </nav>
            <main class="main">Welcome, <?php echo $_SESSION['name']; ?>!</main>
        <?php endif; ?>
        <?php include('footer.php'); ?>
    </div>
</body>
</html>