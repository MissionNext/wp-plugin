<?php
/**
 * @var $form \MissionNext\lib\form\RegistrationForm
 * @var $user_id
 */
$api_url = \MissionNext\lib\core\Context::getInstance()->getConfig()->get('api_base_path');
?>
<h1>The user just registered and is waiting for approval.</h1>

<?php foreach($form->groups as $group): ?>

<h2><?php echo $group->name ?></h2>

    <?php foreach($group->fields as $field): ?>

        <?php if($field->field['symbol_key'] != 'password'): ?>
            <p>
                <?php echo $field->field['default_name'] ?> :
                <?php if (!is_array($field->default)) {
                    echo $field->default;
                } else {
                    foreach($field->default as $item){ ?>
                        <p><?php echo $item; ?></p>
                    <?php }
                } ?>
            </p>
        <?php endif; ?>

    <?php endforeach; ?>

<?php endforeach; ?>

<?php if($api_url): ?>
<a href="<?php echo $api_url ?>/dashboard/user#/<?php echo $user_id ?>">User page</a>
<?php endif; ?>