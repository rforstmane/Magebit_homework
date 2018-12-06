<?php include('connect.php'); ?>
<?php if (!isset($_SESSION['name'])) {
    header('location: main.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css"/>

</head>
<body>
<div class="wrapper">
    <a href="logged_in.php?logout='1'" class="nav__link">SIGN OUT</a>
    <div id="inputwrapper">
        <div class="row">
            <input class="js-attribute-input" name="key"/>
            <input class="js-attribute-input" name="value"/>
            <button class="delete">x</button>
        </div>
    </div>
    <button id="submit" class="button">Submit</button>
</div>


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="main.js"></script>
</body>
</html>