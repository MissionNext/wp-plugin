var FieldTranslation = {

    modal : null,

    init : function(){
        this.modal = jQuery('#field_translations_modal').dialog({
            dialogClass : 'wp-dialog',
            closeOnEscape : true,
            autoOpen: false,
            height: 'auto',
            width: 'auto',
            modal: true,
            buttons: {
                Save: function() {
                    FieldTranslation.saveData(function(){FieldTranslation.modal.dialog( "close" )});
                },
                Close: function() {
                    jQuery( this ).dialog( "close" );
                }
            },
            close: function() {
                jQuery( this ).empty();
            }
        });
    },

    open : function(field_id){

        var data = {
            action: 'mn',
            route: '/model/field/get/translation',
            id: field_id,
            role: role
        };

        jQuery.post(ajaxurl, data, function(response){
            FieldTranslation.fill(response);
            FieldTranslation.modal.dialog( "open" );
        });
    },

    fill : function(data){
        this.modal.html(data);
    },

    collectData : function(){
        return this.modal.find('form').serialize();
    },

    saveData : function(callback){

        var data = {
            action: 'mn',
            route: '/model/field/save/translation',
            field: this.collectData(),
            role: role
        };

        jQuery.post(ajaxurl, data, callback);

    }

};

jQuery(document).ready(function(){
    FieldTranslation.init();
});