<div class="page-header">
    <h1><?php echo sprintf(__('Edit %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></h1>
</div>
<div class="page-content job-form">

    <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal" enctype="multipart/form-data">

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render("_form", array('form' => $form)) ?>

        <div class="control-buttons">
            <div class="left">
                <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                <a class="btn btn-default" href="/job"><?php echo __("Jobs", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
            <div class="right">
                <button type="submit" class="btn btn-success"><?php echo __("Save", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var job_id = <?php echo $form->job['id']; ?>;
    var job_title_field = '<?php echo $job_title_field; ?>';

    jQuery(document).ready(function () {

        jQuery(document).on('click', '.file-delete-icon', function (e) {
            var link = jQuery(this);
            var route = '/delete/job/file';
            var fieldkey = link.data('fieldkey');

            jQuery.ajax
            ({
                url: route,
                type: "POST",
                dataType: "JSON",
                data:
                    {
                        fieldname: fieldkey,
                        jobid: job_id
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

        var job_title_value = jQuery('select[data-key="' + job_title_field + '"]').val();
        jQuery('select[data-key="' + job_title_field + '"]')
            .find('option')
            .remove()
            .end()
            .append('<option value="' + job_title_value + '">' + job_title_value + '</option>');
    });
</script>