<?php
/**
 * @var $userRole
 * @var $affiliates Array
 * @var $role String
 */
// echo "\$userRole = $userRole "; echo "\$role = $role";
// print_r($affiliates);
?>
<div class="page-header">
    <h1><?php echo __('Affiliates', \MissionNext\lib\Constants::TEXT_DOMAIN)?></h1>
</div>
<div class="page-content">
    <?php if($affiliates['approved'] || $affiliates['pending']): ?>
        <table class="table">
            <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?php echo ucfirst(getCustomTranslation($role, $role)) ?></th>
                <?php if ($role == "agency"): ?>
                    <th><?php echo __('Rep Name', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
                <?php else: ?>
                    <th>&nbsp;</th>
                <?php endif; ?>
                <th><?php echo __('Actions', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            </tr>
            </thead>
            <tbody>
            <tr class="approved-header header">
                <td colspan="4"><?php echo __('Approved', \MissionNext\lib\Constants::TEXT_DOMAIN)?></td>
            </tr>
            <?php foreach($affiliates['approved'] as $aff):
                // echo "<tr><td colspan='4'>"; print_r($aff); echo "</td></tr>";
                ?>

                <tr data-requester="<?php echo $aff['affiliate_requester'] ?>" data-approver="<?php echo $aff['affiliate_approver'] ?>">
                    <td class="avatar"><?php echo get_avatar($aff[ $role . '_profile']['email'], 50) ?></td>
                    <?php if ($role == "organization"): ?>
                        <td class="name"><a target="_blank" href="/<?php echo $role ?>/<?php echo $aff[ $role . '_profile']['id'] ?>"><?php echo \MissionNext\lib\UserLib::getUserOrganizationName($aff[$role . '_profile']) ?></a> </td>
                    <?php else: ?>
                        <td class="name"><a target="_blank" href="/<?php echo $role ?>/<?php echo $aff[ $role . '_profile']['id'] ?>"><?php echo \MissionNext\lib\UserLib::getAgencyFullName($aff[$role . '_profile']) ?></a> </td>
                    <?php endif; ?>

                    <?php if ($role == "organization"): ?>
                        <td class="actions"><div><a href="/<?php echo $role ?>/<?php echo $aff[ $role . '_profile']['id'] ?>/jobs"><?php echo __('View Positions', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a></div></td>
                    <?php elseif ($role == "agency"): ?>
                        <td class="rep_name"><?php echo $aff[ $role . '_profile']['profileData']['first_name'] ?> <?php echo $aff[ $role . '_profile']['profileData']['last_name'] ?> </td>
                    <?php else: ?>
                        <td class="rep_name">&nbsp; </td>
                    <?php endif; ?>
                    <td class="actions">
                        <div class="btn btn-link cancel"><?php echo __('Cancel', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr class="pending-header header">
                <td colspan="4"><?php echo __('Pending', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></td>
            </tr>
            <?php foreach($affiliates['pending'] as $aff): ?>
                <tr data-requester="<?php echo $aff['affiliate_requester'] ?>" data-approver="<?php echo $aff['affiliate_approver'] ?>">
                    <td class="avatar"><?php echo get_avatar($aff[ $role . '_profile']['email'], 50) ?></td>
                    <?php if ($role == "organization"): ?>
                        <td class="name"><a target="_blank" href="/<?php echo $role ?>/<?php echo $aff[ $role . '_profile']['id'] ?>"><?php echo \MissionNext\lib\UserLib::getUserOrganizationName($aff[$role . '_profile']) ?></a> </td>
                    <?php else: ?>
                        <td class="name"><a target="_blank" href="/<?php echo $role ?>/<?php echo $aff[ $role . '_profile']['id'] ?>"><?php echo \MissionNext\lib\UserLib::getAgencyFullName($aff[$role . '_profile']) ?></a> </td>
                    <?php endif; ?>

                    <?php if ($role == "organization"): ?>
                        <td class="actions"><div><a href="/<?php echo $role ?>/<?php echo $aff[ $role . '_profile']['id'] ?>/jobs"><?php echo __('View Positions', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></a></div></td>
                    <?php elseif ($role == "agency"): ?>
                        <td class="rep_name"><?php echo $aff[ $role . '_profile']['profileData']['first_name'] ?> <?php echo $aff[ $role . '_profile']['profileData']['last_name'] ?> </td>
                    <?php else: ?>
                        <td class="rep_name">&nbsp; </td>
                    <?php endif; ?>
                    <td class="actions">
                        <?php if($aff['affiliate_approver_type'] == $userRole): ?>
                            <div class="btn btn-link approve"><?php echo __('Approve', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></div>
                        <?php endif; ?>
                        <div class="btn btn-link cancel"><?php echo __('Cancel', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    <?php else: ?>
        <div class="block">
            <?php $none="Yes"; echo __("No affiliates yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </div>
    <?php endif; ?>
    <!--Add Affiliate lines added by Nelson Oct 30, 2016-->
    <!--<? print_r($affiliates); echo "<br>\$userRole = $userRole"; ?>-->
    <?php if ($userRole == "agency"): ?>
        <a class="btn btn-default" href="/organization/search"><?php echo __("Request Affiliation", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
		<?php if($none != "Yes"): ?>
			<a class="btn btn-default" href="https://info.missionnext.org/recruit_candidates.php?appid=<?php echo $site_id ?>" target="_blank"><?php echo __("Manage Affiliate Matches", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a> 
		<?php endif; ?>

    <?php elseif ($userRole == "organization"):

        $sniff_host  = $_SERVER["HTTP_HOST"]; // returns what is after https:// and before first slash
        if (preg_match("/explorenext/",$sniff_host)) {
            $site_id = 3;
        }
        elseif (preg_match("/teachnext/",$sniff_host)) {
            $site_id = 6;
        }
        ?>
        <a class="btn btn-default" href="/agency/search"><?php echo __("Request Affiliation", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a> &nbsp;
        <?php if($none != "Yes"): ?>
        <a class="btn btn-default" href="https://info.missionnext.org/assign_folders.php?appid=<?php echo $site_id ?>" target="_blank"><?php echo __("Assign Folders to Affiliates", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
    <?php endif;
    endif; ?>

</div>

<script>

    jQuery(document).on("click", "table tr td div.approve", function(e){
        var div = jQuery(e.target);
        var tr = div.parents('tr');

        approveAffiliate(tr.attr('data-requester'), tr.attr('data-approver'), function(data){
            if(data['status'] == 'approved'){
                tr.find('td.actions').html("<div class='btn btn-link cancel'><?php echo __('Cancel', \MissionNext\lib\Constants::TEXT_DOMAIN)?></div>");
                var header = tr.siblings('.pending-header');
                tr.detach();
                header.before(tr);
            }
        });
    }).on("click", "table tr td div.cancel", function(e){
        var div = jQuery(e.target);
        var tr = div.parents('tr');

        cancelAffiliate(tr.attr('data-requester'), tr.attr('data-approver'), function(data){
            if(data){
                tr.remove();
            }
        });
    });

    function approveAffiliate(requester, approver, successCallback, errorCallback){

        jQuery.ajax({
            type: "POST",
            url: "/affiliate/approve",
            data: {
                requester_id: requester,
                approver_id: approver
            },
            success: successCallback,
            error: errorCallback,
            dataType: "JSON"
        });
    }

    function cancelAffiliate(requester, approver, successCallback, errorCallback){

        jQuery.ajax({
            type: "POST",
            url: "/affiliate/cancel",
            data: {
                requester_id: requester,
                approver_id: approver
            },
            success: successCallback,
            error: errorCallback,
            dataType: "JSON"
        });
    }
</script>