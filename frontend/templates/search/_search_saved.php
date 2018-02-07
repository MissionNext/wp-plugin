<?php
/**
 * @var $role
 */
?>

<div class="block" id="saved_search">
    <?php renderTemplate("search/_ajax_search_saved", compact('saved', 'role')) ?>
</div>

<script>
    var role = "<?php echo $role ?>";
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/search/search_saved', 'search/search_saved.js', array( 'jquery' ));
?>