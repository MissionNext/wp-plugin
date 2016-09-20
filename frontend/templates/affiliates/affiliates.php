<?php
/**
 * @var $userRole
 * @var $affiliates Array
 * @var $role String
 */

?>
<div class="page-header">
    <h1><?php echo __('Affiliates', \MissionNext\lib\Constants::TEXT_DOMAIN)?></h1>
</div>
<div class="page-content">
    <?php if($affiliates['approved'] || $affiliates['pending']): ?>
        <table class="table">
            <thead>
            <tr>
                <th></th>
                <th><?php echo ucfirst(getCustomTranslation($role, $role)) ?></th>
                <th><?php echo __('Actions', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            </tr>
            </thead>
            <tbody>
            <tr class="approved-header header">
                <td colspan="3"><?php echo __('Approved', \MissionNext\lib\Constants::TEXT_DOMAIN)?></td>
            </tr>
            <?php foreach($affiliates['approved'] as $aff): ?>

                <tr data-requester="<?php echo $aff['affiliate_requester'] ?>" data-approver="<?php echo $aff['affiliate_approver'] ?>">
                    <td class="avatar"><?php echo get_avatar($aff[ $role . '_profile']['email'], 50) ?></td>
                    <td class="name"><a href="/<?php echo $role ?>/<?php echo $aff[ $role . '_profile']['id'] ?>"><?php echo \MissionNext\lib\UserLib::getUserFullName($aff[$role . '_profile']) ?></a></td>
                    <td class="actions">
                        <div class="btn btn-link cancel"><?php echo __('Cancel', \MissionNext\lib\Constants::TEXT_DOMAIN); ?></div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr class="pending-header header">
                <td colspan="3"><?php echo __('Pending', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></td>
            </tr>
            <?php foreach($affiliates['pending'] as $aff): ?>
                <tr data-requester="<?php echo $aff['affiliate_requester'] ?>" data-approver="<?php echo $aff['affiliate_approver'] ?>">
                    <td class="avatar"><?php echo get_avatar($aff[ $role . '_profile']['email'], 50) ?></td>
                    <td class="name"><a href="/<?php echo $role ?>/<?php echo $aff[ $role . '_profile']['id'] ?>"><?php echo \MissionNext\lib\UserLib::getUserFullName($aff[$role . '_profile']) ?></a></td>
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
            <?php echo __("No affiliates yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </div>
    <?php endif; ?>

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