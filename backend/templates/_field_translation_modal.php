<?php
/**
 * @var $role
 */
$languages = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getSiteLanguages();
?>

<div id="field_translations_modal" class="hide">
</div>

<script>
    var role = '<?php echo $role ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/model/field_translation', 'model/field_translation.js', array( 'jquery', 'jquery-ui-dialog', 'jquery-form' ), false, true);
?>