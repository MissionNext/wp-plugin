<?php
/**
 * @var $form \MissionNext\lib\form\Form
 * @var $jobs Array
 */

require_once("connect.inc.php");
if(is_array($jobs)) {
	foreach($jobs as $job) {
	$job_id = $job['id'];
	$sql_sec = "SELECT value FROM job_profile WHERE job_id = $job_id AND field_id = 2";
	$res_sec = pg_query($db_link,$sql_sec) or die("\$sql_sec query failed: <br>$sql_sec");
		if ($row_sec = pg_fetch_array($res_sec)) {
			$second_title[$job_id] = $row_sec[0];
		}
	}
}
pg_close($db_link);
// print_r($second_title);
?>

<div class="page-header">
    <h1><?php echo sprintf(__('New %s', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></h1>
</div>
<div class="page-content job-form">

    <?php if($jobs): ?>
    <div class="block">
        <form role="form" id="populate-from" action="<?php echo $_SERVER[ 'REQUEST_URI' ] ?>" method="GET" class="create-from form-horizontal" enctype="multipart/form-data">

            <div class="form-group">
                <label for="create-from-select" class="col-sm-2 control-label"><?php echo __("Populate from", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></label>
                <div class="col-sm-10">
                    <select name="from" id="create-from-select">
                        <?php foreach($jobs as $job): ?>
                        	<?php $thisjob = $job['id']; $other_title = $second_title[$thisjob]; ?>
                            <option value="<?php echo $job['id'] ?>"><?php echo $job['name'] ?> &#151; <?php echo $other_title ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <button type="button" id="populate-from-btn" class="btn btn-primary"><?php echo __("Populate", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                </div>
            </div>

        </form>
    </div>
    <?php endif; ?>

    <form role="form" id="job-form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="POST" class="form-horizontal">

        <?php \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render("_form", array('form' => $form)) ?>

        <div class="control-buttons">
            <div class="left">
                <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                <a class="btn btn-default" href="/job"><?php echo __("Jobs", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </div>
            <div class="right">
                <button type="submit" class="btn btn-success"><?php echo __("Create", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
            </div>
        </div>
    </form>
</div>
