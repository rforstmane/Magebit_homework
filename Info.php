<?php //var_dump($instance); ?>
<?php if (count($instance->infos) > 0): ?>

    <div class="info">
        <?php foreach ($instance->infos as $info): ?>
            <p><?php echo $info; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>