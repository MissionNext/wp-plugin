var GroupLabelTranslationModal = {

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
                    GroupLabelTranslationModal.callback(GroupLabelTranslationModal.collectData());
                    GroupLabelTranslationModal.modal.dialog( "close" );
                },
                Close: function() {
                    GroupLabelTranslationModal.modal.dialog( "close" );
                }
            },
            close: function() {
                GroupLabelTranslationModal.clean();
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
                GroupLabelTranslationModal.modal.find('tbody td.group-label').text(value['value']);
            } else {
                GroupLabelTranslationModal.modal.find('input[name='+value['id']+']').val(value['value']);
            }
        });

    },

    collectData : function(){
        var serialized = this.modal.find('input').serializeArray();

        jQuery.each(serialized, function(key, value){
            serialized[key]['id'] = serialized[key]['name'];
            delete serialized[key]['name'];
        });

        return serialized;
    },

    clean : function(){
        this.callback = null;
        this.modal.find("input").val("");
    }

};

jQuery(document).ready(function(){
    GroupLabelTranslationModal.init("#group_label_translations");
});