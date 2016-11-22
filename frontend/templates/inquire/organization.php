<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $inquiries
 */
$key = 0;

?>
<div class="page-header">
    <h1><?php echo __("Inquiries list", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content">
    <?php if($inquiries): ?>
    <table class="table result">
        <thead>
        <tr>
            <th>#</th>
            <th><?php echo __('Full name', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Date of inquiry', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Favorite', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Actions', \MissionNext\lib\Constants::TEXT_DOMAIN)?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($inquiries as $job): ?>

            <tr class="header">
                <td class="inquery-colspan" colspan="5"><a href="/job/<?php echo $job['id']?>"><?php echo $job['name'] ?></a></td>
            </tr>

            <?php foreach($job['inquiries'] as $inquirie): ?>

            <tr data-id="<?php echo $inquirie['id'] ?>" data-job-id="<?php echo $job['id'] ?>" data-candidate-id="<?php echo $inquirie['candidate']['id'] ?>">
                <td class="id"><?php echo ++$key ?></td>
                <td class="name"><a href="/candidate/<?php echo $inquirie['candidate']['id'] ?> " target="_blank"><?php echo \MissionNext\lib\UserLib::getUserFullName($inquirie['candidate']) ?></a></td>
                <td><?php echo date('Y-m-d', strtotime($inquirie['updated_at'])) ?></td>
                <td class="favorite" >
                    <div class="favorite-block <?php echo is_integer($inquirie['favorite'])?'favorite':'not-favorite' ?>"></div>
                </td>
                <td>
                    <a class="btn btn-danger inquire-cancel"><?php echo __('Remove', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
                </td>
            </tr>
            <?php endforeach; ?>

        <?php endforeach; ?>

        </tbody>

    </table>
    <?php else: ?>
    <div class="block">
        <?php echo __("No inquiries yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>
    <?php endif; ?>

</div>

<script>

    jQuery(document).on('click', '.inquire-cancel', function(e){

        var row = jQuery(e.target).parents('tr');

        cancelInquireByOrganization(row.attr('data-id'), row.attr('data-job-id'), row.attr('data-candidate-id'), function(data){
            row.remove();
            removeHeaders();
            resetIndexes();
        });
    });

    function resetIndexes(){
        var index = 1;
        var rows = jQuery('table tbody tr td.id');
        jQuery.each(rows, function(key, value){
            jQuery(value).text(index);
            index++;
        });
    }

    function removeHeaders(){
        var rows = jQuery('table.table.result tbody tr');

        jQuery.each(rows, function(key, value){

            value = jQuery(value);

            if(!value.hasClass('header')){
                return;
            }

            var next = value.next();

            if(!next.length || next.hasClass('header')){
                value.remove();
            }
        });
    }

</script>