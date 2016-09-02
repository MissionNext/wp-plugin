<?php
/**
 * @var $languages
 */
?>

<div id="field_notes_translations" title="Field notes">
    <table class="wp-list-table widefat">
        <thead>
            <tr>
                <td></td>
                <td>English</td>
                <?php foreach($languages as $language): ?>
                <td data-key="<?php echo $language['key'] ?>" data-id="<?php echo $language['id'] ?>"><?php echo $language['name'] ?></td>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <tr data-type="before_notes">
                <td>Before the field</td>
                <td data-key="0" data-id="0">
                    <textarea name="0"></textarea>
                </td>
                <?php foreach($languages as $language): ?>
                <td data-key="<?php echo $language['key'] ?>" data-id="<?php echo $language['id'] ?>">
                    <textarea name="<?php echo $language['id'] ?>"></textarea>
                </td>
                <?php endforeach; ?>
            </tr>
            <tr data-type="after_notes">
                <td>After the field</td>
                <td data-key="0" data-id="0">
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

<script>

    var FieldNotesTranslationModal = {

        modal : null,
        callback : null,

        init : function(id){
            this.modal = jQuery(id).dialog({
                dialogClass : 'wp-dialog',
                closeOnEscape : true,
                autoOpen: false,
                height: 'auto',
                width: 'auto',
                modal: true,
                buttons: {
                    Save: function() {
                        FieldNotesTranslationModal.callback(FieldNotesTranslationModal.collectData());
                        FieldNotesTranslationModal.modal.dialog( "close" );
                    },
                    Close: function() {
                        FieldNotesTranslationModal.modal.dialog( "close" );
                    }
                },
                close: function() {
                    FieldNotesTranslationModal.clean();
                }
            });
        },

        open : function(translations, callback){
            this.fill(translations);
            this.callback = callback;
            this.modal.dialog("open");
        },

        fill : function(data){

            jQuery.each(data, function(type, langs){
                jQuery.each(langs, function(key, value){
                    FieldNotesTranslationModal.modal.find('tr[data-type='+type+'] textarea[name='+value['lang_id']+']').val(value['value']);
                });
            });

        },

        collectData : function(){
            var trs = this.modal.find('tbody tr');

            var data = {};

            jQuery.each(trs, function(k1, v1){

                var tr = jQuery(v1);
                var langs = [];

                jQuery.each(tr.find('textarea'), function(k2, v2){

                    var area = jQuery(v2);

                    langs.push ( {
                        lang_id : area.attr('name'),
                        value: area.val()
                    });

                });

                data[tr.attr('data-type')] = langs;

            });

            return data;
        },

        clean : function(){
            this.callback = null;
            this.modal.find("textarea").val("");
        }

    };

    jQuery(document).ready(function(){
        FieldNotesTranslationModal.init("#field_notes_translations");
    });

</script>