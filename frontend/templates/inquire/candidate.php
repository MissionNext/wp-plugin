<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $inquiries
 */
$sniff_host = $_SERVER["HTTP_HOST"]; // returns what is after https:// and before first slash 
// echo "Candidate <br>"; print_r($inquiries);

?>
<div class="page-header">
    <h1><?php echo __("Inquiry list", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content">
    <?php if($inquiries): ?>
    <table class="table result">
        <thead>
        <tr>
            <th>#</th>
            <th><?php echo sprintf(__('%s title', \MissionNext\lib\Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_JOB))) ?></th>
            <th><?php echo __('Second Title', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo ucfirst(getCustomTranslation(\MissionNext\lib\Constants::ROLE_ORGANIZATION)) ?></th>
            <th><?php echo __('Favorite', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Actions', \MissionNext\lib\Constants::TEXT_DOMAIN)?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($inquiries as $key => $inquire): ?>
        <tr data-id="<?php echo $inquire['id'] ?>">
            <td class="id"><?php echo $key+1 ?></td>
            <td class="name"><a target="_blank" href="/job/<?php echo $inquire['id'] ?>"><?php echo $inquire['name'] ?></a> </td>
            <td class="name"><?php echo $inquire['profileData']['second_title'] ?> </td>
            <td class="name"><a target="_blank" href="/organization/<?php echo $inquire['organization']['id'] ?>"><?php echo $inquire['organization']['profileData']['organization_name'] ?></a></td>
            <td class="favorite">
                <div class="favorite-block <?php echo isset($inquire['favorite'])?'favorite':'not-favorite' ?>"></div>
            </td>
            <td>
                <a class="btn btn-danger inquire-cancel"><?php echo __('Cancel', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </td>
        </tr>
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
    jQuery(document).on("click", "table tr td .inquire-cancel", function(e){

        var tr = jQuery(e.target).parents("tr");
        cancelInquire(tr.attr('data-id'), function(data){
            tr.remove();
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
</script>