<?php if (count($instance->errors) > 0): ?>
    <div class="error">
        <?php foreach ($instance->errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
