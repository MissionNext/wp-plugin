<?php
/**
 * @var $languages
 * @var $field
 */
?>
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $field['id'] ?>"/>
    <table class="wp-list-table widefat">
        <thead>
            <tr>
                <th>English</th>
                <?php foreach($languages as $language): ?>
                <th>
                    <?php echo $language['name'] ?>
                </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="<?php echo count($languages) + 1 ?>">Label</td>
            </tr>
            <tr>
                <td><?php echo $field['default_name'] ?></td>
                <?php foreach($languages as $language): ?>
                <td>
                    <input name="name[<?php echo $language['id'] ?>]" type="text" value="<?php echo $field['name'][$language['id']] ?>"/>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php if($field['default_choices']): ?>
                <tr>
                    <td colspan="<?php echo count($languages) + 1 ?>">Choices</td>
                </tr>
                <?php foreach($field['default_choices'] as $id => $choice):?>

                <?php $meta = json_decode($field['meta'][$id], true); ?>
                <?php if(!empty($meta)): ?>
                <tr style="background-color: #eee;">
                    <td>
                        <?php echo $meta['group'][0]; ?>
                        <input type="hidden" name="groups[<?php echo $id ?>][0]" value="<?php echo $meta['group'][0]; ?>">
                    </td>
                    <?php foreach($languages as $language): ?>
                    <td>
                        <input type="text" name="groups[<?php echo $id ?>][<?php echo $language['id'] ?>]" value="<?php if(isset($meta['group'][$language['id']])) echo $meta['group'][$language['id']];?>">
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endif; ?>

                <tr>
                    <td><?php echo $choice ?></td>
                    <?php foreach($languages as $language): ?>
                    <?php if($choice): ?>
                    <td>
                        <input name="choices[<?php echo $language['id'] ?>][<?php echo $id ?>]" type="text" value="<?php if(isset($field['choices'][$language['id']][$id])) echo $field['choices'][$language['id']][$id] ?>"/>
                    </td>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tr>

                <?php endforeach; ?>
            <?php endif; ?>
            <?php if($field['default_note']): ?>
            <tr>
                <td colspan="<?php echo count($languages) + 1 ?>">Tooltip</td>
            </tr>
            <tr>
                <td><?php echo $field['default_note'] ?></td>
                <?php foreach($languages as $language): ?>
                    <td>
                        <input name="note[<?php echo $language['id'] ?>]" type="text" value="<?php echo $field['note'][$language['id']] ?>"/>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</form>