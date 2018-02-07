jQuery(document).ready(function(){
    console.log(EmailPopup);
    EmailPopup.init();
    console.log(EmailPopup);
});

var EmailPopup = {

    popup : '',
    fromInput : '',
    ccMe : '',
    toInput : '',
    subjectInput : '',
    bodyInput : '',
    nameTo : '',
    nameFrom : '',

    dialogConfig : {
        autoOpen: false,
        height: 'auto',
        width: '500',
        modal: true,
        draggable: false,
        resizable: false,
        buttons: {
            sendButton : function(){

                EmailPopup.send(
                    function(data, textStatus, jqXHR){
                        EmailPopup.clear();
                        EmailPopup.popup.dialog('close');
                    },
                    function(data, textStatus, jqXHR){
                        EmailPopup.popup.dialog('close');
                    }
                );

            },
            cancelButton : function(){
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

        this.fromInput = this.popup.find("#from");
        this.ccMe = this.popup.find("#cc_me");
        this.toInput = this.popup.find("#to");
        this.subjectInput = this.popup.find("#email-subject");
        this.bodyInput = this.popup.find("#email-body");
        this.nameTo = this.popup.find("#name-to");
        this.nameFrom = this.popup.find("#name-from");
    },

    open : function (from, to, from_name, to_name, subject, body, cc_me){

        this.fromInput.val(from);
        this.ccMe.val(cc_me);
        this.toInput.val(to);
        this.subjectInput.val(subject);
        this.bodyInput.val(body);
        this.nameFrom.val(from_name);
        this.nameTo.val(to_name);

        this.popup.dialog('open');
    },

    send: function(success, error){
        var cc_me = '';
        if (this.ccMe.is(':checked')) {
            cc_me = 'copy';
        }

        var data = {
            to : this.toInput.val(),
            from: this.fromInput.val(),
            to_name: this.nameTo.val(),
            cc_me: cc_me,
            subject: this.subjectInput.val(),
            body: this.bodyInput.val()
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