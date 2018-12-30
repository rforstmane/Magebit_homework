<?php include('connect.php'); ?>
<?php if (!isset($_SESSION['name'])) {
    header('location: main');
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css"/>

</head>
<body>
<?php


$attributes = new Attributes($instance);
$attr_rows = $attributes->getAttributesByUserId();
?>

<form method="post" action="account">
    <div class="wrapper">
        <nav class="nav">
            <a href="logged_in.php?logout='1'" class="nav__link">SIGN OUT</a>
        </nav>
        <main class="main">
            <?php include('info.php'); ?>
            <div id="inputwrapper" class="input-wrapper">
                <?php foreach ($attr_rows as $result) { ?>
                    <div class="row">
                        <input type="hidden" name="id[]" value="<?php echo $result["id"]?>">
                        <input class="js-attribute-input" type="text" name="keey[]" value="<?php echo $result["keey"]; ?>"/>
                        <input class="js-attribute-input" type="text" name="value[]"
                               value="<?php echo $result["value"]; ?>"/>
                        <button class="delete">x</button>
                    </div>
                <?php } ?>
                <div class="row">
                    <input type="hidden" name="id[]">
                    <input class="js-attribute-input" type="text" name="keey[]"/>
                    <input class="js-attribute-input" type="text" name="value[]"/>
                    <button class="delete">x</button>
                </div>
            </div>
            <button id="submit" class="button" type="submit" name="submit">Save changes</button>
        </main>
        <?php include('footer.php'); ?>
    </div>
</form>


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="main.js"></script>
</body>
</html>