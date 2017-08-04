<div class="page-header">
    <h1><?php echo sprintf(__('Edit %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></h1>
</div>
<div class="page-content job-form">

    <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
<?php 
$url_string = $_SERVER['REQUEST_URI']; 
$this_jobid = intval(preg_replace('/[^0-9]+/', '', $url_string), 10); // echo $this_jobid; // extracts intgers from a string 
require_once("connect.inc.php");
	$sql_pri = "SELECT name FROM jobs WHERE id = $this_jobid";
	$res_pri = pg_query($db_link,$sql_pri) or die("\$sql_pri query failed: <br>$sql_pri");
		if ($row_pri = pg_fetch_array($res_pri)) {
			$first_title = $row_pri[0];
		}
	$sql_sec = "SELECT value FROM job_profile WHERE job_id = $this_jobid AND field_id = 2";
	$res_sec = pg_query($db_link,$sql_sec) or die("\$sql_sec query failed: <br>$sql_sec");
		if ($row_sec = pg_fetch_array($res_sec)) {
			$second_title = $row_sec[0];
		}
pg_close($db_link);
print ("<br>EDITING JOB: $first_title&#151;$second_title"); 
?>
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