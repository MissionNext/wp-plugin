<?php

/**
 * @var array $languages
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/forms/outro', 'forms/outro.js', array( 'jquery', 'jquery-ui-dialog', 'jquery-form' ));

?>

<div id="form_outro_translations" title="Form outro">
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