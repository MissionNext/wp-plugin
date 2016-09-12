<div id="email-popup" style="display: none">
    <div class="field">
        <label for="email-to"><?php echo __('To', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
        <input id="email-to" type="text" disabled="disabled"/>
    </div>
    <div class="field">
        <label for="email-from"><?php echo __('From', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
        <input id="email-from" type="text" disabled="disabled"/>
    </div>
    <div class="field">
        <label for="email-subject"><?php echo __('Subject', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
        <input id="email-subject" type="text"/>
    </div>
    <div class="field">
        <label for="email-body"><?php echo __('Body', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
        <textarea id="email-body" cols="30" rows="10"></textarea>
    </div>
</div>

<script>

    jQuery(document).ready(function(){
        EmailPopup.init();
    });

    var EmailPopup = {

        popup : '',
        fromInput : '',
        toInput : '',
        subjectInput : '',
        bodyInput : '',

        dialogConfig : {
            autoOpen: false,
            height: 'auto',
            width: '500',
            modal: true,
            draggable: false,
            resizable: false,
            buttons: {
                "<?php echo __('Send', \MissionNext\lib\Constants::TEXT_DOMAIN) ?>" : function(){

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
                "<?php echo __('Cancel', \MissionNext\lib\Constants::TEXT_DOMAIN) ?>" : function(){
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
        },

        open : function (from, to, subject, body){

            this.fromInput.val(from);
            this.toInput.val(to);
            this.subjectInput.val(subject);
            this.bodyInput.val(body);

            this.popup.dialog('open');
        },

        send: function(success, error){

            var data = {
                to : this.toInput.val(),
                from: this.fromInput.val(),
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




</script>