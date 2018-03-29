<?php
    $types = \MissionNext\lib\form\fields\BaseField::$types;

    $symbol_keys = array();

    foreach ( $fields as $field ) {
        $symbol_keys[] = $field['symbol_key'];
    }


?>

<style>
    #new_field > div {
        margin: 10px;
    }
    #new_field label{
        vertical-align: top;

        display: inline-block;
        min-width: 100px;
    }
    #new_field input {
        border: 1px solid #ccc;
    }
    div#field_default_value {
        display: inline-block;
    }

    #ui-datepicker-div {
        padding: 10px;
        background: #eee;
        border-radius: 10px;
        border: 1px solid #ccc;
    }
    .text-danger{
        color: red;
    }
    #field_choices{
        display: inline-block;
    }
    #choices{
        padding-bottom: 10px;
    }
    #choices .choice{
        padding-left: 10px;
        margin: 5px 0;
        border-left: 10px solid #ccc;
    }
    #choices .group{
        padding-left: 10px;
        margin: 5px 0;
        border-left: 10px solid #369;
    }
    /*#add_field_choice{*/
        /*padding-left: 20px;*/
    /*}*/
</style>

<button class="button" onclick="jQuery('#field_dialog').dialog('open')"><?php echo 'New field' ?></button>

<div id="field_dialog" style="display: none">
    <form id="new_field" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

        <input type="hidden" name="form" value="field"/>
        <input type="hidden" name="action" value="create"/>
        <input type="hidden" name="id"/>

        <div>
            <label for="field_type">Type</label>
            <select name="type" id="field_type">
                <?php foreach($types as $type): ?>
                    <option value="<?php echo $type['key'] ?>" data-key="<?php echo $type['key'] ?>"><?php echo $type['label'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="field_label">Label</label>
            <input autocomplete="off" type="text" name="label" id="field_label"/>
        </div>

        <div>
            <label for="field_symbol_key">Unique key</label>
            <input type="text" name="symbol_key" id="field_symbol_key"/>
        </div>

        <div>
            <label for="field_default_value">Default value</label>
            <input type="text" name="default_value" id="field_default_value"/>
        </div>

        <div>
            <label for="field_size">Size</label>
            <select name="size" id="field_size">
                <option value="small">Small</option>
                <option value="medium" selected="selected" >Medium</option>
                <option value="large">Large</option>
            </select>
        </div>

        <div style="display: none" >
            <label for="field_add_empty">Add Empty</label>
            <input type="checkbox" name="add_empty" id="field_add_empty" value="1"/>
        </div>

        <div class="text-danger" id="no-preference-help" style="display: none;">
            Choice started with "(!)" will be treated as "No preference" choice
        </div>

        <div style="display: none;">
            <label for="field_choice">Choices</label>
            <div id="field_choices">
                <div id="choices"></div>
                <div id="add_field_choice">
                    <textarea id="field_choice"></textarea>
                    <button id="field_choice_add_button" class="button" type="button">Add choice</button>
                </div>
                <div id="add_field_choice_group">
                    <textarea id="field_choice_group"></textarea>
                    <button id="field_choice_group_add_button" class="button" type="button">Add group</button>
                </div>
            </div>
        </div>

        <div id="field-tooltip">
            <label for="field_tooltip_input">Tooltip</label>
            <textarea name="tooltip" id="field_tooltip_input"></textarea>
        </div>

    </form>
</div>

<script type="text/javascript">
    var fields = '<?php echo json_encode($symbol_keys) ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/model/new_field', 'model/new_field.js', array( 'jquery', 'jquery-ui-dialog', 'jquery-ui-sortable', 'jquery-ui-datepicker' ), false, true);
?>