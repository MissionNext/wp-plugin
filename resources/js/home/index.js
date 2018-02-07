jQuery(document).ready(function () {
    jQuery.get('/get/user/favs', { role: role, userid: userId})
        .success( function (data) {
            response = JSON.parse(data);
            if (typeof response.affiliatesCount != 'undefined') {
                jQuery('.affiliates-icon').html("Affiliates<br />" + response.affiliatesCount);
            }
            if (typeof response.favoritesCount != 'undefined') {
                jQuery('.favorites-icon').html('Favorites<br />' + response.favoritesCount);
            }
            if (typeof response.inquiriesCount != 'undefined') {
                jQuery('.inquiries-icon').html('Inquiries<br />' + response.inquiriesCount);
            }
        });

    jQuery.get('/get/user/subscriptions', { userid: userId})
        .success(function (data) {
            response = JSON.parse(data);
            if ("candidate" == role) {
                var subsTable = jQuery('.subscriptions-table');
                subsTable.html('');
                jQuery.each(response.subscriptions, function (index, value) {
                    link = getLinkHtml(appKey, value);
                    subsTable.append('<tr><td>' + link + '</td><td></td></tr>');
                });
                jQuery.each(response.candidateSubs, function (index, value) {
                    appUrl = getAppLink(value.app_id);
                    subsTable.append('<tr><td>' +
                        '<a class="btn btn-default" disabled target="_blank" href="' + appUrl + '/dashboard">' + value.app_name + '</a>' +
                        '</td><td>' +
                        '<a class="btn btn-default" href="/subscription/add/' + value.app_id + '">SignUp for Free</a>' +
                        '</td></tr>');
                });
            } else {
                var subsList = jQuery('.subscription-list');
                subsList.html('');
                jQuery.each(response.subscriptions, function (index, value) {
                    link = getLinkHtml(appKey, value);
                    subsList.append(link);
                });
            }
        });
});