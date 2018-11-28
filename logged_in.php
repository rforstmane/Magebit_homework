<?php include('connect.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Magebit homework</title>
</head>
<body>
    <div>
        <?php if (isset($_SESSION['success'])): ?>
            <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['name'])): ?>
            <p>Welcome, <?php echo $_SESSION['name']; ?></p>
            <p><a href="logged_in.php?logout='1">Logout</a> </p>
        <?php endif; ?>
    </div>
</body>
</html>