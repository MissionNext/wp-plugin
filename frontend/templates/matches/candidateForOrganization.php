<?php
/**
 * @var Array $user
 * @var String $userRole
 * var Array $candidates
   shows candidate list for organizations
*/

$percentages = [10, 20, 30, 40, 50, 60, 70, 80, 90];
$years = [2001, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017];

?>
<div class="page-header">
    <h1><?php echo sprintf(__("%s Matches to Your Organization Profile", \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_CANDIDATE))) ?></h1>
</div>
<div class="page-content">
	
	<div class="control-buttons">
        <div class="left">
            <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a>
        </div>
    </div>
        <div class="col-sm-12 block">
        <?php echo __("Results with match rate below", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        <select class="update-year" id="percentage_filter">
            <?php foreach($percentages as $percentage){
                if($rate == $percentage)
                    echo '<option value="' . $percentage . '" selected="true">' . $percentage . '</option>';
                else
                    echo '<option value="' . $percentage . '">' . $percentage . '</option>';
            }?>
        </select> 
        <?php echo __("not shown. <br>Folder icons clickable to expand/collapse. Refresh screen to update favorites and note icons. ", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>

    <div class="block">
        <?php echo __("Results with profile updates before", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>
        <select class="update-year" id="update_year" name="update_year">
            <?php foreach($years as $year){
                if($updates == $year)
                    echo '<option value="' . $year . '" selected="true">' . $year . '</option>';
                else
                    echo '<option value="' . $year . '">' . $year . '</option>';
            }?>
        </select>
        <?php echo __("not shown.", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>
    </div>

    <?php if($candidates): ?>
        <?php renderTemplate("common/_candidate_table", array('role' => 'candidate', 'items' => $candidates, 'messages' => $messages, 'userRole' => $userRole, 'userId' => $userId, 'sort_by' => $sort_by, 'order_by' => $order_by, 'organization_id' => $user['id'])) ?>
        <?php renderTemplate("common/_pager", compact('page', 'pages', 'sort_by', 'order_by')) ?>
    <?php else: ?>
        <div class="block">
            <?php echo __("Sorry, no matches yet. (Matches are run overnight US Time)", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </div>
    <?php endif; ?>

    <div class="control-buttons">
        <div class="left">
            <a class="btn btn-default" href="/dashboard"><?php echo __("Dashboard", \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a>
        </div>
    </div>
</div>

<script>
    var redirect_url = '<?php echo $_SERVER['REDIRECT_URL']?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/matches/candidateForOrganization', 'matches/candidateForOrganization.js', array( 'jquery' ), false, true);
?>

