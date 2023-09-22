<?php

/**
 * @var $fields Array
 * @var $defaults Array
 * @var $restFields Array
 * @var $formName String
 * @var $predefinedFields Array
 * @var $canHaveOuterDependencies Boolean
 * @var $canHaveInnerDependencies Boolean
 * @var $canHaveExpandedFields Boolean
 * @var $canHavePrivateGroups Boolean
 * @var $languages
 * @var $translations
 * @var string $form_intro
 * @var string $form_outro
 * @var string $main_fields
 * @var string $main_fields_translations
 */

?>

<?php
function getFieldBySymbolKey($fields, $symbol_key) {
    foreach ($fields as $group) {
        foreach ($group['fields'] as $fieldItem) {
            if ($fieldItem['symbol_key'] == $symbol_key) {

                return $fieldItem['filtered_choices'];
            }
        }
    }
}
?>
<style>

    #fields, form {
        margin-top: 25px;
    }

    #fields {
        display: inline-block;
        vertical-align: top;
        border: 1px solid #ccc;
        width: 250px;
        min-height: 100px;
    }

    #search{
        margin: 5px;
        width: 240px;
    }

    #fields .field,
    form div.group .field
    {
        border: 1px solid #cccccc;
        margin: 5px;
        padding: 10px 25px;
    }

    form div.group .field{
        min-height: 28px;
    }

    form div.group .field > input[type=checkbox]{
        margin: 5px ;
    }

    .predefined-field .tooltip,
    .notes
    {
        float: right;
    }

    form {
        vertical-align: top;
        display: inline-block;
        width: 650px;
        margin: 15px;
        padding: 15px;
        border: 1px solid #ccc;
    }

    form div.group {
        margin: 20px 0;
        padding: 15px;
        min-height: 100px;
        border: 1px solid #ccc;
    }

    form div.group .dependant{
        float: right;
        margin: auto;
    }

    form div.group > select.depend-select,
    form div.group > input.is-private
    {
        float: right;
        vertical-align: middle;
    }
    form div.group > select.depend-select
    {
        width: 220px;
    }

    form div.group > input.is-private{
        margin: 6px 10px;
    }

    form div.group > span.private
    {
        padding: 4px 0;

        float: right;
    }

    form div.predefined-group div
    {
        border: 1px solid #cccccc;
        margin: 5px;
        padding: 10px 25px;
    }

    form div.predefined-group div.predefined-field
    {
        min-height: 28px;
    }

    form div.field .option {
        display: inline-block;
        float: right;
    }

    form div.field .option > *{
        padding: 0 5px;
    }

    form div.depends-option-wrapper {
        overflow: hidden;
        margin: 5px 0 0 180px;
    }
    form div.depends-option-wrapper .depend-option-select{
        width: 240px;
    }
    form div.subgroup-depends-option-wrapper {
        margin: 5px 0 0 0;
        overflow: hidden;
        width: 400px;
    }
    form div.subgroup-depends-option-wrapper .depend-option-select{
        width: 170px;
    }
    .dark-grey{
        background-color: #DDDDDD;
        border
    }
    .add-sub-group{
        display: block;
        margin: 5px;
        cursor: pointer;
    }

</style>

<div id="fields">

    <input type="text" id="search" onkeyup="search(this)"/>

    <?php foreach($restFields as $field): ?>

    <div class="drag" data-type="<?php echo $field['type'] ?>">
        <div class="field  <?php echo ('select' == $field['type'] || 'radio' == $field['type']) ? 'dark-grey' : ''; ?>">
            <label for="<?php echo $field['symbol_key'] ?>"><?php echo $field['name'] ?></label>
            <input type="hidden" data-orig-name="<?php echo $field['symbol_key'] ?>" value="<?php echo $field['symbol_key'] ?>"/>
        </div>
    </div>

    <?php endforeach ?>

</div>

<form id="builder-form" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" data-name="<?php echo $formName ?>" >

    <h3>Form</h3>

    <button class="button button-default intro" type="button">Introduction</button>
    <input class="intro" type="hidden" name="<?php echo $formName; ?>[form_intro]" value="<?php echo htmlspecialchars($form_intro); ?>">

    <button class="button button-default outro" type="button">Outro</button>
    <input class="outro" type="hidden" name="<?php echo $formName; ?>[form_outro]" value="<?php echo htmlspecialchars($form_outro); ?>">

    <?php if($predefinedFields): ?>
    <div class="group">
        <?php if($formName == 'registration'): ?>
        <input type="text" name="<?php echo $formName; ?>[main_fields][group_name]" value="<?php echo $main_fields ?>">
        <button class="button button-primary translations" type="button">Translations</button>
        <input type="hidden" name="<?php echo $formName; ?>[main_fields][translations]" value="<?php echo htmlspecialchars($main_fields_translations); ?>">
        <?php endif; ?>

        <div class="predefined-group">
            <?php foreach($predefinedFields as $predefinedField): ?>
            <div class="predefined-field">
                <?php echo $predefinedField['field']; ?>
                <input type="hidden" name="<?php echo $formName; ?>[<?php echo $predefinedField['key']; ?>][tooltip]" value="<?php echo htmlspecialchars($predefinedField['tooltip']); ?>">
                <button class="button button-default tooltip" type="button">Tooltip</button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="groups">
        <?php foreach($fields as $group ): ?>

            <div class="group" data-name="<?php echo $formName . '[' . $group['symbol_key'] . ']' ?>">

                <input type="text" name="<?php echo $formName . "[" . $group['symbol_key'] . "][group_name]" ?>" value="<?php echo $group['name'] ?>"/>
                <button class="button button-primary translations" type="button">Translations</button>
                <input type="hidden" name="<?php echo $formName . "[" . $group['symbol_key'] . "][translations]" ?>" value="<?php echo isset($translations[$group['id']])?htmlspecialchars(json_encode($translations[$group['id']])):'' ?>"/>

                <?php if($canHavePrivateGroups): ?>
                <span class="private">Private</span>
                <input class="is-private" type="checkbox" name="<?php echo $formName . "[" . $group['symbol_key'] . "][is_private]" ?>" value="1" <?php if(isset($group['meta']['is_private']) && $group['meta']['is_private']) echo 'checked=""checked' ?>/>
                <?php endif;?>

                <?php if($canHaveOuterDependencies): ?>
                <input type="hidden" name="<?php echo $formName . "[" . $group['symbol_key'] . "][is_outer_dependent]" ?>" value="<?php echo $group['is_outer_dependent'] ?>"/>
                <select class="depend-select" name="<?php echo $formName . "[" . $group['symbol_key'] . "][depends_on]" ?>" data-default="<?php echo $group['depends_on'] ?>"></select>
                <div class="depends-option-wrapper" style="<?php echo $group['depends_on_option'] ? "display: block" : "display: none"?>;">
                    <label>Depends on option</label>
                    <?php if ($group['depends_on_option']) {
                        $field_choices = getFieldBySymbolKey($fields, $group['depends_on']);
                        ?>
                        <select class="depend-option-select" name="<?php echo $formName . "[" . $group['symbol_key'] . "][depends_on_option]" ?>" data-default="<?php echo $group['depends_on_option'] ?>">
                            <option value=""></option>
                            <?php foreach ($field_choices as $choiceItem) { ?>
                                <option value="<?php echo $choiceItem?>" <?php echo ($choiceItem == $group['depends_on_option']) ? 'selected="selected"' : ''; ?>><?php echo $choiceItem?></option>
                            <?php } ?>
                        </select>
                    <?php } else { ?>
                        <select class="depend-option-select" name="<?php echo $formName . "[" . $group['symbol_key'] . "][depends_on_option]" ?>" data-default="<?php echo $group['depends_on_option'] ?>"></select>
                    <?php } ?>
                </div>

                <?php endif; ?>

                <?php foreach($group['fields'] as $field): ?>

                    <div class="drag" data-type="<?php echo $field['type'] ?>">

                        <div class="field  <?php echo ('select' == $field['type'] || 'radio' == $field['type']) ? 'dark-grey' : ''; ?>" data-type="<?php echo $field['type'] ?>" data-choices='
                         <?php if (("radio" == $field['type'] || "select" == $field['type']) && $field['filtered_choices']) {
                            echo json_encode($field['filtered_choices']);
                        } ?>'>

                            <label for="<?php echo $field['symbol_key'] ?>"><?php echo $field['name'] ?></label>
                            <input name="<?php echo $formName . '[' . $group['symbol_key'] . '][fields][' . $field['symbol_key'] . '][symbol_key]' ?>" type="hidden" data-orig-name="<?php echo $field['symbol_key'] ?>" value="<?php echo $field['symbol_key'] ?>"/>

                            <?php if(isset($defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['before_notes'], $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['after_notes'])): ?>
                            <input type="hidden" name="<?php echo $formName . '[' . $group['symbol_key'] . '][fields][' . $field['symbol_key'] . '][notes]' ?>" value="<?php echo htmlspecialchars(json_encode(array('before_notes' => $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['before_notes'], 'after_notes' => $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['after_notes']))) ?>"/>
                            <?php else: ?>
                            <input type="hidden" name="<?php echo $formName . '[' . $group['symbol_key'] . '][fields][' . $field['symbol_key'] . '][notes]' ?>" value="<?php echo htmlspecialchars(json_encode(array('before_notes' => array(), 'after_notes' => array()))); ?>"/>
                            <?php endif; ?>

                            <?php if($canHaveExpandedFields && ($field['type'] == 'radio' || $field['type'] == 'select' || 'custom_marital' == $field['type'])): ?>
                                <div class="option expanded">
                                    <span>Expanded</span>
                                    <input <?php if($defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['meta']['search_options']['is_expanded']) echo 'checked="checked"' ?> type="checkbox" value="1" name="<?php echo $formName . '[' . $group['symbol_key'] . '][fields][' . $field['symbol_key'] . '][is_expanded]' ?>"/>
                                </div>
                            <?php endif; ?>

                            <?php if($canHaveInnerDependencies): ?>
                                <input type="checkbox" class="dependant" <?php if(isset($field['group'])) echo 'checked="checked"' ?> title="Dependant">
                            <?php endif; ?>

                            <button class="button button-default notes" type="button">Notes</button>

                            <?php if( $canHaveInnerDependencies && isset($field['group'])) { ?>
                                <?php foreach ($field['group'] as $innerGroup) { ?>
                                    <div class="group" data-name="<?php echo $formName . '[' . $innerGroup['symbol_key'] . ']' ?>">

                                        <input type="text" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][group_name]" ?>" value="<?php echo $innerGroup['name'] ?>"/>
                                        <button class="button button-primary translations" type="button">Translations</button>
                                        <input type="hidden" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][is_outer_dependent]" ?>" value="<?php echo $innerGroup['is_outer_dependent'] ?>"/>
                                        <input type="hidden" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][depends_on]" ?>" value="<?php echo $innerGroup['depends_on'] ?>"/>
                                        <input type="hidden" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][translations]" ?>" value="<?php echo isset($translations[$innerGroup['id']])?htmlspecialchars(json_encode($translations[$innerGroup['id']])):'' ?>"/>

                                        <?php if($canHavePrivateGroups): ?>
                                            <span class="private">Private</span>
                                            <input class="is-private" type="checkbox" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][is_private]" ?>" value="1" <?php if(isset($innerGroup['meta']['is_private']) && $innerGroup['meta']['is_private']) echo 'checked=""checked' ?>/>
                                        <?php endif;?>

                                        <?php if ('radio' == $field['type'] || 'select' == $field['type']) { ?>
                                            <div class="subgroup-depends-option-wrapper">
                                                <label>Depends on option</label>
                                                <select class="depend-option-select" name="<?php echo $formName . "[" . $innerGroup['symbol_key'] . "][depends_on_option]" ?>" data-default="<?php echo $innerGroup['depends_on_option'] ?>">
                                                    <option value=""></option>
                                                    <?php foreach($field['filtered_choices'] as $choice) { ?>
                                                        <option value="<?php echo $choice; ?>" <?php if ($innerGroup['depends_on_option'] == $choice) { echo 'selected="selected"'; } ?>><?php echo $choice; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } ?>


                                        <?php foreach($innerGroup['fields'] as $innerField): ?>

                                            <div class="drag" data-type="<?php echo $field['type'] ?>">
                                                <div class="field  <?php echo ('select' == $field['type'] || 'radio' == $field['type']) ? 'dark-grey' : ''; ?>">
                                                    <label for="<?php echo $innerField['symbol_key'] ?>"><?php echo $innerField['default_name'] ?></label>
                                                    <input name="<?php echo $formName . '[' . $innerGroup['symbol_key'] . '][fields][' . $innerField['symbol_key'] . '][symbol_key]' ?>" type="hidden" data-orig-name="<?php echo $innerField['symbol_key'] ?>" value="<?php echo $innerField['symbol_key'] ?>"/>
                                                    <input type="hidden"
                                                           name="<?php echo $formName . '[' . $innerGroup['symbol_key'] . '][fields][' . $innerField['symbol_key'] . '][notes]' ?>"
                                                           value="<?php echo htmlspecialchars(json_encode(array(
                                                               'before_notes' => $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['group'][0]['fields'][$innerField['symbol_key']]['meta']['before_notes'],
                                                               'after_notes' => $defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['group'][0]['fields'][$innerField['symbol_key']]['meta']['after_notes']))) ?>"/>

                                                    <?php if($canHaveExpandedFields && ($innerField['type'] == 'radio' || $innerField['type'] == 'select' || 'custom_marital' == $innerField['type'])): ?>
                                                        <div class="option expanded">
                                                            <span>Expanded</span>
                                                            <input <?php if($defaults[$group['symbol_key']]['fields'][$field['symbol_key']]['group']['fields'][$innerField['symbol_key']]['meta']['search_options']['is_expanded']) echo 'checked="checked"' ?> type="checkbox" value="1" name="<?php echo $formName . '[' . $innerGroup['symbol_key'] . '][fields][' . $innerField['symbol_key'] . '][is_expanded]' ?>"/>
                                                        </div>
                                                    <?php endif; ?>

                                                    <button class="button button-default notes" type="button">Notes</button>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>

                                    </div>
                                <?php } ?>


                                <?php if ('radio' == $field['type'] || 'select' == $field['type']) { ?>
                                    <a class="add-sub-group">
                                        <img src="<?php echo getResourceUrl('/resources/images/plus_button.png') ?>" />
                                    </a>
                                <?php } ?>

                            <?php } ?>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>
        <?php endforeach; ?>

        <?php if(empty($fields)): ?>
            <div class="group" data-name="<?php echo $formName . '[group1]' ?>">

                <input type="text" name="<?php echo $formName . "[group1][group_name]" ?>" value="Group 1"/>
                <button class="button button-primary translations" type="button">Translations</button>
                <input type="hidden" name="<?php echo $formName . "[group1][translations]" ?>" value=""/>

                <?php if($canHaveOuterDependencies): ?>
                <input type="hidden" name="<?php echo $formName . "[group1][is_outer_dependent]" ?>" value="1"/>
                <select class="depend-select" name="<?php echo $formName . "[group1][depends_on]" ?>"></select>
                <div class="depends-option-wrapper">
                    <label>Depends on option</label>
                    <select class="depend-option-select" name="<?php echo $formName . "[group1][depends_on_option]" ?>"></select>
                </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

    </div>

    <button onclick="addGroup()" type="button" class="button"> <?php echo 'Add group' ?> </button>

    <?php submit_button(); ?>
</form>

<?php renderTemplate("_group_form_translation_modal", compact('languages')) ?>
<?php renderTemplate("forms/_field_tooltip_translation_modal", compact('languages')) ?>
<?php renderTemplate("forms/_field_notes_translation_modal", compact('languages')) ?>
<?php renderTemplate("forms/_form_intro_translation_modal", compact('languages')) ?>
<?php renderTemplate("forms/_form_outro_translation_modal", compact('languages')) ?>

<script type="text/javascript">
    var canHaveInnerDependencies = '<?php echo $canHaveInnerDependencies ?>';
    var canHaveExpandedFields = '<?php echo $canHaveExpandedFields ?>';
    var canHavePrivateGroups = '<?php echo $canHavePrivateGroups ?>';
    var canHaveOuterDependencies = '<?php echo $canHaveOuterDependencies ?>';
    var resourceUrl = '<?php echo getResourceUrl("/resources/images/plus_button.png"); ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/forms/base_building', 'forms/base_building.js', array( 'jquery' ), false, true);
?>
