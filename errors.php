<?php if(count($errors) > 0): ?>
    <?php foreach ($errors as $error): ?>
        <p class="error" style="color: red"><?php echo $error; ?></p>
    <?php endforeach; ?>
<?php endif; ?>
