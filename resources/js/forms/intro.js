var FormIntroTranslationModal =
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
                            FormIntroTranslationModal.callback(FormIntroTranslationModal.collectData());
                            FormIntroTranslationModal.modal.dialog('close');
                        },
                        Close: function()
                        {
                            FormIntroTranslationModal.modal.dialog('close');
                        }
                    },
                close: function()
                {
                    FormIntroTranslationModal.clean();
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
            jQuery.each(data, function(key, value)
            {
                FormIntroTranslationModal.modal.find('textarea[name=' + value['id'] + ']').val(value['value']);
            });
        },

        collectData: function()
        {
            var data = this.modal.find('textarea').serializeArray();

            jQuery.each(data, function(key, value)
            {
                data[key]['id'] = data[key]['name'];
                data[key]['value'] = data[key]['value'].replaceAll('"', '\'');

                delete data[key]['name'];
            });

            return data;
        },

        clean: function()
        {
            this.callback = null;
            this.modal.find('textarea').val('');
        }
    };

jQuery(document).ready(function()
{
    FormIntroTranslationModal.init('#form_intro_translations');
});