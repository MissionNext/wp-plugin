<?php

/**
 * @var array $languages
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/forms/field_tooltip', 'forms/field_tooltip.js', array( 'jquery', 'jquery-ui-dialog', 'jquery-form' ), false, true);

?>

<div id="field_tooltip_translations">
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
                <td data-key="en" data-id="0">
                    <input name="0">
                </td>
                <?php foreach($languages as $language): ?>
                <td data-key="<?php echo $language['key'] ?>" data-id="<?php echo $language['id'] ?>">
                    <input name="<?php echo $language['id'] ?>">
                </td>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
</div>