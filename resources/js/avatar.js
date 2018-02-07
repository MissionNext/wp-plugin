jQuery(document).on('change', "#avatar_upload", function(e){
    var block = jQuery('#avatar');
    block.addClass('loading');
    block.find('.spinner32').css('visibility', 'visible');
    jQuery(e.target).parent('form').submit();
}).on('click', '#avatar .action .upload', function(e){
    jQuery('#avatar_upload').click();
}).on('click', '#avatar .action .delete', function(e){
    document.location = '/avatar/delete';
});