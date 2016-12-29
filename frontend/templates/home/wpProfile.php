<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var \MissionNext\lib\form\Form $form
 */
$group = reset($form->groups);
global $shortcode_tags;
?>
<div class="page-header">
    <h1><?php echo __('User Account', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content">
    <div class="row">
        <div class="col-sm-12">

            <form action="<?php echo $_SERVER[ 'REQUEST_URI' ] ?>" method="POST" role="form" class="form-horizontal">

                <input type="hidden" name="action" value="profile"/>

                <div id="<?php echo $group->group['symbol_key'] ?>"  class="group" data-key="<?php echo $group->group['symbol_key'] ?>">

                    <div class="form-group">

                        <?php echo $group->fields['username']->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                        <div class="col-sm-9">
                            <?php echo $group->fields['username']->printField(array('disabled' => "disabled")) ?>
                        </div>
                    </div>

                    <div class="form-group">

                        <?php if($group->fields['email']->hasError()): ?>
                            <?php foreach($group->fields['email']->getError() as $error): ?>
                                <div class="col-sm-offset-2 col-sm-10 text-danger">
                                    <?php echo $error ?>
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>

                        <?php echo $group->fields['email']->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                        <div class="col-sm-9">
                            <?php echo $group->fields['email']->printField() ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php if($group->fields['old_password']->hasError()): ?>
                            <?php foreach($group->fields['old_password']->getError() as $error): ?>
                                <div class="col-sm-offset-2 col-sm-10 text-danger">
                                    <?php echo $error ?>
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>

                        <?php echo $group->fields['old_password']->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                        <div class="col-sm-9">
                            <?php echo $group->fields['old_password']->printField() ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php if($group->fields['password']->hasError()): ?>
                            <?php foreach($group->fields['password']->getError() as $error): ?>
                                <div class="col-sm-offset-2 col-sm-10 text-danger">
                                    <?php echo $error ?>
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>

                        <?php echo $group->fields['password']->printLabel(array('class' => 'col-sm-3 control-label')) ?>
                        <div class="col-sm-9">
                            <?php echo $group->fields['password']->printField() ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success"><?php echo __("Save", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></button>
                    </div>
                </div>

            </form>
<?php 
// print_r($user);
$user_role   = $user['role']; // echo "\$user_role = $user_role<br>";
if ($user_role == "candidate") {
$factor		 = rand(10,99); // generate random two-digit number
$factored	 = $factor * $user['id'];  // factored is the product of the random number and user_id 
$pass_string = $factor.$factored; // pass this string, then extract user_id as $factored / $factor 

$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after http:// and before first slash 
if (preg_match("/explorenext/",$sniff_host)) { 
	$site = 3 * $factor;
	
}
elseif (preg_match("/teachnext/",$sniff_host)) { 
	$site = 6 * $factor;
}
?>               
                <div class="buttons"> 
            	<a href="https://info.missionnext.org/print_profile.php?uid=<?php echo $pass_string ?>&site=<?php echo $site ?>" title="Printer Friendly Display" target="_blank"><button class="btn btn-default">Your Profile</button></a>
            	</div>
<?php
} // if ($user_role == "candidate")
?>        

        </div>
    </div>
</div>