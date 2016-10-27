<?php
/**
 * @var $form \MissionNext\lib\form\Form
 */

?>
<div class="page-header">
    <h1><?php echo __('Profile', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content <?php echo $userRole; ?>-form">
    <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render('_form', compact('form')) ?>

        <div class="form-group">
            <div class="col-sm-12">
                <?php if ($profileCompleted) { ?>
                    <input type="submit" name="submit" class="btn btn-success" title="Allows Program to Continue. All required fields must be completed." value="<?php echo __("Submit", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>" />
                <?php } else { ?>
                    <input type="submit" name="submit" class="btn btn-success" title="Allows Program to Continue. All required fields must be completed." value="<?php echo __("Complete? Submit", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>" />
                    <!--<input type="submit" name="savelater" class="btn btn-success" title="Saves Entries Only" value="<?php echo __("Save for Later", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>" />-->
                <?php }?>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var formHasChanged = false;
    var submitted = false;
    var user_id = <?php echo $userId; ?>;
    var userrole = '<?php echo $userRole; ?>';

    jQuery(document).ready(function () {

        jQuery(document).on('change', 'form.form-horizontal input, form.form-horizontal select, form.form-horizontal textarea', function (e) {
            formHasChanged = true;
        });

        jQuery(document).on('click', '.file-delete-icon', function (e) {
            var link = jQuery(this);
            var route = '/delete/file';
            var fieldkey = link.data('fieldkey');

            jQuery.ajax
            ({
                url: route,
                type: "POST",
                dataType: "JSON",
                data:
                {
                    fieldname: fieldkey,
                    userid: user_id
                },
                success: function(data)
                {
                    if ("success" == data.status) {
                        jQuery('#view-' + fieldkey).hide();
                        jQuery('#uploaded-' + fieldkey).show();
                    }

                }
            });
        });

        window.onbeforeunload = function (e) {
            if (formHasChanged && !submitted) {
                var message = "You have not saved your changes.", e = e || window.event;
                if (e) {
                    e.returnValue = message;
                }
                return message;
            }
        }
        jQuery("form").submit(function() {
            submitted = true;
        });
    });
</script>