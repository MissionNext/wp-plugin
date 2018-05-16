var EmailPopup = {

    popup : '',
    fromInput : '',
    toInput : '',
    subjectInput : '',
    bodyInput : '',
    spinner : '',

    dialogConfig : {
        autoOpen: false,
        height: 'auto',
        width: '500',
        modal: true,
        draggable: false,
        resizable: false,
        buttons: {
            Send : function(){

                EmailPopup.send(
                    function(data, textStatus, jqXHR){
                        EmailPopup.clear();
                        EmailPopup.popup.dialog('close');
                        EmailPopup.spinner.hide();
                    },
                    function(data, textStatus, jqXHR){
                        EmailPopup.popup.dialog('close');
                        EmailPopup.spinner.hide();
                    }
                );

            },
            Cancel : function(){
                EmailPopup.popup.dialog('close');
            }
        },
        close: function() {
            var modal = jQuery(this);
            modal.find('[name="id"]').val('');
            modal.find('textarea.message').val('');
        }
    },

    init : function(){
        this.popup = jQuery("#email-popup").dialog(this.dialogConfig);

        this.fromInput = this.popup.find("#email-from");
        this.toInput = this.popup.find("#email-to");
        this.subjectInput = this.popup.find("#email-subject");
        this.bodyInput = this.popup.find("#email-body");

        this.spinner = this.popup.find("#loader");
    },

    open : function (from, to, subject, body){

        this.fromInput.val(from);
        this.toInput.val(to);
        this.subjectInput.val(subject);
        this.bodyInput.val(body);

        this.popup.dialog('open');
    },

    send: function(success, error){
        this.spinner.show();
        var data = {
            to : this.toInput.val(),
            from: this.fromInput.val(),
            subject: this.subjectInput.val(),
            body: this.bodyInput.val(),
        };

        jQuery.ajax({
            type: "POST",
            url: "/email/send",
            data: data,
            success: success,
            error: error,
            dataType: "JSON"
        });
    },

    clear: function(){
        this.toInput.val("");
        this.fromInput.val("");
        this.subjectInput.val("");
        this.bodyInput.val("");
    }
};