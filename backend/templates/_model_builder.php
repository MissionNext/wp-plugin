<style>
    .field {
        margin: 5px 5px;
        padding: 5px 5px;
        font-weight: bold;
        font-size: 12px;
        border: 1px solid #ccc;
        position: relative;
    }
    .field.required > label{
        color: red;
    }
    .field .constraints{
        font-size: 10px;
        margin-left: 30px;
        font-weight: normal;
    }
    .field .constraints li *{
        vertical-align: middle;
    }
    .field .constraints li button{
        margin-left: 10px;
    }
    .field .add_constraint .additional_data{
        padding: 10px;
    }
    .field .add_constraint .additional_data span{
        margin: 0 15px;
    }
    div.check-all{
        margin: 10px 15px;
    }
    .field .tooltip{
        position: absolute;
        top: 5px;
        left: 125px;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background: #83A508;
        z-index: 100;
    }
    .field .tooltip > *{
        display: inline-block;
        vertical-align: middle;
    }
    #delete-dialog{
        padding: 15px;
    }
    #delete-dialog p{
        text-align: center;
    }
</style>

<div>
    <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('_new_field_form', array("fields" => $fields)) ?>
</div>

<div class="check-all">
    <!--<input type="checkbox" id="check-all" onchange="check_all(this)"/>
    <label for="check-all">Check All</label>-->
    Search: 
    <input id="search" type="text" onkeyup="search_field(this)">
</div>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">

    <input type="hidden" name="form" value="model"/>

    <?php

    foreach($fields as $field){

        $isset = isset($defaults[$field['symbol_key']]);

        $checked = $isset?'checked="checked"':'';
        ?>

        <div class="field <?php if(in_array($field['symbol_key'], \MissionNext\lib\Constants::$predefinedFields[$role])) echo 'required' ?>" data-key="<?php echo $field['symbol_key'] ?>" data-id="<?php echo $field['id'] ?>" data-params='<?php echo htmlspecialchars(json_encode($field), ENT_QUOTES, 'UTF-8') ?>'>
            <input id="<?php echo $field['symbol_key'] ?>" type="checkbox" value="<?php echo $field['id'] ?>" name="model[<?php echo $field['symbol_key'] ?>][id]" <?php echo $checked ?> />
            <label for="<?php echo $field['symbol_key'] ?>"> <?php echo $field['name'] ?></label>

            <div style="display: none;" class="tooltip">
                <?php echo $fieldsGroup->fields[$field['symbol_key']]->printLabel(); ?>
                <div>
                    <?php echo $fieldsGroup->fields[$field['symbol_key']]->printField(array('disabled' => 'disabled')); ?>
                </div>
            </div>

            <button class="button edit" type="button"><?php echo "Edit" ?></button>
            <button class="button translate" type="button"><?php echo "Translate" ?></button>
            <?php if(!in_array($field['symbol_key'], \MissionNext\lib\Constants::$predefinedFields[$role])): ?>
            <button class="button delete" type="button"><?php echo "Delete" ?></button>
            <?php endif; ?>

            <button class="button toggle<?php if( isset($defaults[$field['symbol_key']]) && $defaults[$field['symbol_key']]['constraints']) echo ' button-primary' ?>" type="button" <?php  if(!$isset) echo 'style="display:none;"'; ?>>V</button>

            <div class="hide" style="display: none">
                <div class="constraints" >
                    <ul>
                        <?php if($isset): foreach($defaults[$field['symbol_key']]['constraints'] as $constraint): ?>
                            <li>
                                <input type="hidden" name="model[<?php echo $field['symbol_key'] ?>][constraints][<?php echo $constraint['key'] ?>]" value="<?php echo $constraint['orig'] ?>" data-key="<?php echo $constraint['key'] ?>"/>
                                <span><?php echo $constraint['orig'] ?></span>
                                <button type="button" class="button">X</button>
                            </li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>

                <div class="add_constraint">
                    <select>

                        <option value="">Select constraint:</option>

                        <?php foreach($validators as $validator): ?>

                            <?php if( !isset($validator['types']) || in_array($field['type'], $validator['types']) ):

                                $options = isset($validator['options'])?"data-options='" . json_encode($validator['options']) . "'":'';

                                $hide = $isset && in_array($validator['key'], array_keys($defaults[$field['symbol_key']]['constraints']));

                                ?>
                                <option <?php if($hide) echo 'style="display:none;"' ?> value="<?php echo $validator['key'] ?>" <?php echo $options ?>><?php echo $validator['label'] ?></option>
                            <?php endif; ?>

                        <?php endforeach ?>
                    </select>
                    <button type="button" class="button">Add constraint</button>
                    <div class="additional_data">
                    </div>
                </div>
            </div>


        </div>

    <?php
    }

    ?>
    <button type="submit" class="button button-primary" value="model">Save</button>
</form>

<div id="delete-dialog" title="Are you sure?">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
        <p>
            This field will be deleted from all websites and user profiles.
        </p>
        <p>
            Are you sure?
        </p>
    </p>
</div>

<?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('_field_translation_modal', array('role' => $role)) ?>

<script>
    var role = '<?php echo $role ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/model/model_builder', 'model/model_builder.js', array( 'jquery', 'jquery-ui-dialog' ));
?>