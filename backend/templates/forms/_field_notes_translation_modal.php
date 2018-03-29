<?php
/**
 * @var $languages
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/forms/field_notes', 'forms/field_notes.js', array( 'jquery', 'jquery-ui-dialog' ), false, true);
?>

<div id="field_notes_translations" title="Field notes">
    <table class="wp-list-table widefat">
        <thead>
            <tr>
                <td></td>
                <td>English</td>
                <?php foreach($languages as $language): ?>
                <td data-key="<?php echo $language['key'] ?>" data-id="<?php echo $language['id'] ?>"><?php echo $language['name'] ?></td>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <tr data-type="before_notes">
                <td>Before the field</td>
                <td data-key="0" data-id="0">
                    <textarea name="0"></textarea>
                </td>
                <?php foreach($languages as $language): ?>
                <td data-key="<?php echo $language['key'] ?>" data-id="<?php echo $language['id'] ?>">
                    <textarea name="<?php echo $language['id'] ?>"></textarea>
                </td>
                <?php endforeach; ?>
            </tr>
            <tr data-type="after_notes">
                <td>After the field</td>
                <td data-key="0" data-id="0">
                    <textarea name="0"></textarea>
                </td>
                <?php foreach($languages as $language): ?>
                    <td data-key="<?php echo $language['key'] ?>" data-id="<?php echo $language['id'] ?>">
                        <textarea name="<?php echo $language['id'] ?>"></textarea>
                    </td>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
</div>