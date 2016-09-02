<?php
/**
 * @var $messages Array
 */
?>

<?php if(isset($messages['error'])): ?>
    <div class="block bg-danger error"><?php echo $messages['error'] ?></div>
<?php endif; ?>

<?php if(isset($messages['notice'])): ?>
    <div class="block bg-success notice"><?php echo $messages['notice'] ?></div>
<?php endif; ?>