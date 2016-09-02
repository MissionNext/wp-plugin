<?php
/**
 * @var $languages
 * @var $defaults
 * @var $keys
 */
?>

<form id="languages_form" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

    <table class="wp-list-table widefat">
        <thead>
            <tr>
                <th>Key</th>
                <th>English</th>
                <?php foreach($languages as $language): ?>
                <th><?php echo $language['name'] ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($keys as $key => $label): ?>
            <tr>
                <td><?php echo $label ?></td>
                <td><input type="text" name="translations[<?php echo $key ?>][0]" value="<?php echo isset($defaults[$key][0])?$defaults[$key][0]:'' ?>"/></td>
                <?php foreach($languages as $language): ?>
                <td><input type="text"  name="translations[<?php echo $key ?>][<?php echo $language['id'] ?>]" value="<?php echo isset($defaults[$key][$language['id']])?$defaults[$key][$language['id']]:'' ?>"/></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <button type="submit" class="button button-primary" value="model">Save</button>
</form>
