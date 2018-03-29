var EmailPopup = {

    popup : '',
    fromInput : '',
    toInput : '',
    subjectInput : '',
    bodyInput : '',
    captcha : '',
    captchaImage : '',
    prefix : '',
    captchaError : '',
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
                        if (typeof data.error != 'undefined') {
                            EmailPopup.captchaError.html(data.error);
                        } else {
                            EmailPopup.clear();
                            EmailPopup.popup.dialog('close');
                        }
                        EmailPopup.spinner.hide();
                    },
                    function(data, textStatus, jqXHR){
                        EmailPopup.popup.dialog('close');
                        EmailPopup.spinner.hide();
                    }
                );

            },
            Cancel : function(){
                EmailPopup.captchaError.html('');
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

        this.captcha = this.popup.find("#captcha");
        this.captchaImage = this.popup.find("#captcha-image");
        this.captchaError = this.popup.find("#captcha-error");

        this.spinner = this.popup.find("#loader");
    },

    open : function (from, to, subject, body, captcha, prefix){

        this.fromInput.val(from);
        this.toInput.val(to);
        this.subjectInput.val(subject);
        this.bodyInput.val(body);

        this.captcha.val('');
        this.captchaImage.attr('src', captcha);

        this.prefix = prefix;

        this.popup.dialog('open');
    },

    send: function(success, error){
        this.spinner.show();
        var data = {
            to : this.toInput.val(),
            from: this.fromInput.val(),
            subject: this.subjectInput.val(),
            body: this.bodyInput.val(),
            captcha: this.captcha.val(),
            prefix: this.prefix,
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
        this.captcha.val("");
        this.captchaError.html('');
    }
};