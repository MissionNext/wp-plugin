<?php
/**
 * @var $languages
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/model/group_form_translation', 'model/group_form_translation.js', array( 'jquery', 'jquery-ui-dialog', 'jquery-form' ));
?>

<div id="group_label_translations" title="Label translations">
    <table class="wp-list-table widefat">
        <thead>
            <tr>
                <td>English</td>
                <?php foreach($languages as $language): ?>
                <td data-key="<?php echo $language['key'] ?>" data-id="<?php echo $language['id'] ?>"><?php echo $language['name'] ?></td>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="group-label"></td>
                <?php foreach($languages as $language): ?>
                <td data-key="<?php echo $language['key'] ?>" data-id="<?php echo $language['id'] ?>">
                    <input name="<?php echo $language['id'] ?>" type="text"/>
                </td>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
</div>