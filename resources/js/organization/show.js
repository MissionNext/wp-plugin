jQuery(document).on('click', '#make_favorite', function(e){
    addFavorite(organization_id, 'organization',function(data){
        jQuery('#remove_from_favorites').attr('data-id', data['id']).removeClass('hide');
        jQuery('#make_favorite').addClass('hide');
    });
}).on('click', '#remove_from_favorites', function(e){
    removeFavorite(jQuery(e.target).attr('data-id'),function(data){
        jQuery('#remove_from_favorites').attr('data-id', false).addClass('hide');
        jQuery('#make_favorite').removeClass('hide');
    });
}).on('click', '#sendEmail', function (e) {
    EmailPopup.init();
    EmailPopup.open(from, to, from_name, to_name);
});