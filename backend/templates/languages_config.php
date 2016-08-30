<?php
/**
 * @var $languages
 * @var $default
 * @var $default_language
 */
?>

<style>

    #languages_form{
        padding: 15px;
    }

    .default_language {
        margin-bottom: 10px;
    }

    .default_language label{
        min-width: 150px;
        display: inline-block;
    }

    .languages{
        margin-bottom: 15px;
    }

    .languages .language {
        padding: 10px 0 10px 0;
    }

    .languages .language label{
        min-width: 150px;
        display: inline-block;
    }

</style>

<form id="languages_form" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

    <div class="default_language">
        <label for="default_language">Default Language:</label>
        <select name="default_language" id="default_language">
            <option <?php if($default_language == 0) echo 'selected="selected"' ?> value="0">English</option>
            <?php foreach($default as $lang): ?>
                <?php if(isset($languages[$lang])): ?>
                <option <?php if($lang == $default_language) echo 'selected="selected"' ?> value="<?php echo $lang ?>"><?php echo $languages[$lang] ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="languages">

        <div class="language">
            <label for="language_en">English</label>
            <input id="language_en" type="checkbox" value="0" checked="checked" disabled="disabled"/>
        </div>

        <?php foreach($languages as $key => $language): ?>
            <div class="language">
                <label for="language_<?php echo $key ?>"><?php echo $language ?></label>
                <input id="language_<?php echo $key ?>" type="checkbox" name="languages[]" value="<?php echo $key ?>" <?php if(in_array($key, $default)) echo 'checked="checked"' ?>/>
            </div>
        <?php endforeach; ?>

    </div>

    <button type="submit" class="button button-primary" value="model">Save</button>
</form>

<script>

    jQuery(document).on('change', '.languages .language input[type="checkbox"]', function(e){
        updateDefaultLanguages();
    });

    function updateDefaultLanguages(){
        var select = jQuery("#default_language");
        var default_value = select.val();
        var inputs = jQuery(".languages .language input[type='checkbox']:checked");

        select.empty();
        jQuery.each(inputs, function(key, value){

            value = jQuery(value);
            var label = value.siblings('label').text();
            var val = value.val();

            if(val == default_value){
                select.append("<option selected='selected' value="+val+">"+label+"</option>");
            } else {
                select.append("<option value="+val+">"+label+"</option>");
            }
        });


    }

</script>