<?php

/**
 * @var array $languages
 */

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

<script>
    var FieldTooltipTranslationModal =
    {
        modal: null,
        callback: null,

        init: function(id)
        {
            this.modal = jQuery(id).dialog
            ({
                dialogClass: 'wp-dialog',
                closeOnEscape: true,
                autoOpen: false,
                height: 'auto',
                width: 'auto',
                modal: true,
                buttons:
                {
                    Save: function()
                    {
                        FieldTooltipTranslationModal.callback(FieldTooltipTranslationModal.collectData());
                        FieldTooltipTranslationModal.modal.dialog('close');
                    },
                    Close: function()
                    {
                        FieldTooltipTranslationModal.modal.dialog('close');
                    }
                },
                close: function()
                {
                    FieldTooltipTranslationModal.clean();
                }
            });
        },

        open: function(translations, callback)
        {
            this.fill(translations);
            this.callback = callback;
            this.modal.dialog('open');
        },

        fill: function(data)
        {
            jQuery.each(data, function(name, value)
            {
                FieldTooltipTranslationModal.modal.find('input[name=' + name + ']').val(value);
            });
        },

        collectData: function()
        {
            data = {};

            var array = this.modal.find('input').serializeArray();

            jQuery.each(array, function(i, e)
            {
                data[e.name] = e.value;
            });

            return data;
        },

        clean: function()
        {
            this.callback = null;
            this.modal.find('input').val('');
        }
    };

    jQuery(document).ready(function()
    {
        FieldTooltipTranslationModal.init('#field_tooltip_translations');
    });
</script>