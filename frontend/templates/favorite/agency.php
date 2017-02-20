<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var Array $favorites
 * @var String $role
 This page is seen be agencies / agency reps
 */
?>
<div class="page-header">
    <h1><?php echo __("Favorites", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></h1>
</div>
<div class="page-content">
    <?php if($favorites): ?>
    <table class="table table-bordered result">
        <thead>
        <tr>
            <th>#</th>
            <th><?php echo __('Full name', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Notes', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
            <th><?php echo __('Actions', \MissionNext\lib\Constants::TEXT_DOMAIN)?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($favorites as $key => $favorite): ?>
        <tr data-id="<?php echo $favorite['data']['id'] ?>" data-fav-id="<?php echo $favorite['id'] ?>" data-name="<?php echo \MissionNext\lib\UserLib::getUserFullName($favorite['data']) ?>">
            <td class="id"><?php echo $key+1 ?></td>
            <td class="name"><a href="/<?php echo $role ?>/<?php echo $favorite['data']['id'] ?>" target="_blank"><?php echo \MissionNext\lib\UserLib::getUserFullName($favorite['data']) ?></a></td>
            <td class="note" data-note="<?php echo htmlentities($favorite['notes']) ?>">
                <div <?php if(!$favorite['notes']) echo 'class="no-note"' ?>></div>
            </td>
            <td>
                <a class="btn btn-danger favorite-remove"><?php echo __('Unfavorite', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></a>
            </td>
        </tr>
        <?php endforeach; ?>

        </tbody>

    </table>
    <?php else: ?>
    <div class="block">
        <?php echo __("No favorites yet.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
    </div>
    <?php endif; ?>

</div>

<div id="note" title="<?php echo __('Note', \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" style="display: none">
    <input type="hidden" name="role" value="<?php echo $role ?>"/>
    <input type="hidden" name="id" value=""/>
    <div class="help">
        <p class="role"><?php echo __("Enter or update a brief note about ", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
<!--            <span>--><?php //echo ucfirst(getCustomTranslation($role)) ?><!--</span>:-->
<!--            --><?php //echo __("Notation Re:", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        <span class="name"></span>:</p>
    </div>
    <textarea cols="25" rows="5" class="message"></textarea>
</div>

<script>

    jQuery(document).on('click', '.favorite-remove', function(e){

        var row = jQuery(e.target).parents('tr');

        removeFavorite(row.attr('data-fav-id'), function(data){
            row.remove();
            resetIndexes();
        });
    }).on('click', 'table tr td.note div', function(e){

            var tr = jQuery(e.target).parents('tr');

            openNote(
                tr.data('id'),
                jQuery(e.target).parents('td').attr('data-note'),
                tr.attr('data-name')
            );
        }
    );

    jQuery(document).ready(function(){
        jQuery('#note').dialog({
            autoOpen: false,
            height: 'auto',
            width: 'auto',
            modal: true,
            draggable: false,
            resizable: false,
            buttons: {
                "<?php echo __("Save", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" : function(){

                    var modal = jQuery(this);
                    var role = modal.find('[name="role"]').val();
                    var id = modal.find('[name="id"]').val();
                    var message = modal.find('textarea.message').val();

                    var data = {
                        role : role,
                        id: id,
                        note: message.trim()
                    };

                    jQuery.ajax({
                        type: "POST",
                        url: "/note/change",
                        data: data,
                        success: function(data, textStatus, jqXHR){

                            var tr = jQuery('table.result tr[data-id="'+data.user_id+'"]');

                            tr.find('td.note').attr('data-note', data.notes);
                            tr.find('td.note div').attr( 'class', data.notes ? '' : 'no-note');

                            modal.dialog('close');
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            modal.dialog('close');
                        },
                        dataType: "JSON"
                    });

                },
                "<?php echo __("Cancel", \MissionNext\lib\Constants::TEXT_DOMAIN); ?>" : function(){
                    jQuery(this).dialog('close');
                }
            },
            close: function() {
                var modal = jQuery(this);
                modal.find('[name="id"]').val('');
                modal.find('textarea.message').val('');
            }
        });
    });

    function openNote(id, text, name){

        var modal = jQuery('#note');

        modal.find('[name="id"]').val(id);
        modal.find('textarea.message').val(text?text:' ');

        modal.find('.help .name').html(name);

        modal.dialog('open');
    }

    function resetIndexes(){
        var index = 1;
        var rows = jQuery('table tbody tr td.id');
        jQuery.each(rows, function(key, value){
            jQuery(value).text(index);
            index++;
        });
    }

</script>