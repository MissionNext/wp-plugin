var FolderTranslationModal = {

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
                    FolderTranslationModal.callback(FolderTranslationModal.collectData());
                    FolderTranslationModal.modal.dialog( "close" );
                },
                Close: function() {
                    FolderTranslationModal.modal.dialog( "close" );
                }
            },
            close: function() {
                FolderTranslationModal.clean();
            }
        });
    },

    open : function(translations, callback){
        this.fill(translations);
        this.callback = callback;
        this.modal.dialog("open");
    },

    fill : function(data){

        jQuery.each(data, function(key, value){
            if(value['id'] == 0){
                FolderTranslationModal.modal.find('tbody td.group-label').text(value['value']);
            } else {
                FolderTranslationModal.modal.find('input[name='+value['id']+']').val(value['value']);
            }
        });

    },

    collectData : function(){
        return this.modal.find('input').serializeArray();
    },

    clean : function(){
        this.callback = null;
        this.modal.find("input").val("");
    }

};

jQuery(document).ready(function(){
    FolderTranslationModal.init("#folder_translations_modal");
});