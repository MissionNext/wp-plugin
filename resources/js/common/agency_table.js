function OpenInNewTab(url) {
    var win = window.open(url, '_blank');
    win.focus();
}

jQuery(document).on('click', 'table.result tr td.note div', function(e){

        var tr = jQuery(e.target).parents('tr');

        openNote(
            tr.data('id'),
            jQuery(e.target).parents('td').attr('data-note'),
            tr.attr('data-name'),
            tr.find('.folder select').val()
        );
    }
).on('change', 'table.result tr td.folder select', function(e){
    changeFolder(jQuery(e.target).parents('tr'), countFolderItems);
}).ready(function(){
    if ('agency' == userRole) {
        jQuery('#note').dialog({
            autoOpen: false,
            height: 'auto',
            width: '500',
            modal: true,
            draggable: false,
            resizable: false,
            buttons: {},
            close: function() {
                var modal = jQuery(this);
                modal.find('[name="id"]').val('');
                modal.find('textarea.message').val('');
            }
        });
    } else {
        jQuery('#note').dialog({
            autoOpen: false,
            height: 'auto',
            width: '500',
            modal: true,
            draggable: false,
            resizable: false,
            buttons: {
                Save : function(){

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
                Cancel : function(){
                    jQuery(this).dialog('close');
                }
            },
            close: function() {
                var modal = jQuery(this);
                modal.find('[name="id"]').val('');
                modal.find('textarea.message').val('');
            }
        });
    }


    var table = jQuery('table.result');

    table.find('th.sortable')
        .each(function(){

            var th = jQuery(this),
                thIndex = th.index(),
                inverse = false;

            th.click(function(){

                table.find('tr.header').each(function(){
                    jQuery(this).nextUntil('.header').find('td').filter(function(){
                        return jQuery(this).index() === thIndex;

                    }).sortElements(function(a, b){

                        var a_obj = jQuery(a);
                        a = a_obj.text();
                        var parent_a = a_obj.parents('tr');
                        var prior_a = parent_a.attr('data-prior');
                        var b_obj = jQuery(b);
                        b = b_obj.text();
                        var parent_b = b_obj.parents('tr');
                        var prior_b = parent_b.attr('data-prior');

                        if( !isNaN(parseInt(a)) && !isNaN(parseInt(b)) ){
                            a = parseInt(a);
                            b = parseInt(b);
                        } else if( !isNaN(parseInt(a)) && !isNaN(parseInt(b)) ){
                            a = parseInt(a);
                            b = parseInt(b);
                        }


                        if( (prior_a && prior_b) || (!prior_a && !prior_b) ){
                            return a > b ?
                                inverse ? -1 : 1
                                : inverse ? 1 : -1;
                        } else if(prior_a){
                            return -1;
                        } else {
                            return 1;
                        }

                    }, function(){
                        // parentNode is the element we want to move
                        return this.parentNode;

                    })
                });

                table.find('th.asc').removeClass('asc');
                table.find('th.desc').removeClass('desc');
                th.addClass(inverse?'asc':'desc');
                resetIndexes();

                inverse = !inverse;
            });

        });
}).on('click', 'table.result tr.folder-title', function(e){
    triggerFolder(this);
});

function openNote(id, text, name, folder){

    var modal = jQuery('#note');

    modal.find('[name="id"]').val(id);
    modal.find('textarea.message').val(text?text:' ');

    modal.find('.help .name').html(name);
    modal.find('.help .folder span').html(folder);

    modal.dialog('open');
}

function changeFolder(row, callback){

    row = jQuery(row);

    var folder = row.find('td.folder select').val();

    jQuery.ajax({
        type: "POST",
        url: "/folder/change",
        data: {
            role: row.parents('table').attr('data-role'),
            id: row.attr('data-id'),
            folder: folder
        },
        success: function(data, textStatus, jqXHR){
            if (typeof data.error != "undefined" && data.error.length > 0) {
                jQuery('#folder-message').dialog({
                    autoOpen: false,
                    height: '300',
                    width: '300',
                    modal: true,
                    buttons: {
                        Close : function(){
                            jQuery(this).dialog('close');
                        }
                    },
                    close: function() {
                        jQuery(this).empty();
                    }
                });
                var dialog = jQuery('#folder-message');

                dialog.html("<p>" + data.error + "</p>");

                dialog.dialog('open');

            } else {
                var group = row.siblings("tr.folder-title[data-name='"+data.folder+"']");

                row.detach();

                group.after(row);

                if(!group.hasClass('open-folder')){
                    row.hide();
                }

                resetGroups();
                resetIndexes();

                if(callback){
                    callback()
                }
            }

        },
        error: function(jqXHR, textStatus, errorThrown){

        },
        dataType: "JSON"
    });

}

function resetGroups(){
    var rows = jQuery('table.result tr.folder-title');

    jQuery.each(rows, function(key, value){
        value = jQuery(value);
        var next = value.next();

        if(next.length > 0 && !next.hasClass('folder-title')){
            value.removeClass('hide');
        } else {
            value.addClass('hide');
        }

    });

}

function resetIndexes(){

    var index = 1;

    var rows = jQuery('table.result tr:visible');

    jQuery.each(rows, function(key, value){

        value = jQuery(value);

        if(value.hasClass('folder-title')){
            index = 1;
        } else {
            value.find('td:first').html(index);
            index++;
        }

    });
}

function triggerFolder(folder){

    folder = jQuery(folder);

    if(folder.hasClass('open-folder')){
        folder.nextUntil('.folder-title').hide();
    } else {
        folder.nextUntil('.folder-title').show();
    }

    folder.toggleClass('open-folder');

}

function countFolderItems(){

    var folders = jQuery('table.result tr.folder-title');

    jQuery.each(folders, function(k, v){
        var folder = jQuery(v);
        var length = folder.nextUntil('.folder-title').length;
        folder.find('td span').text(length);
    });

}

if(matching) {
    jQuery(document).on('click', 'table.result tr td.match-highlight div', function(){
        var spinner = jQuery(this).siblings('.spinner');
        spinner.show();
        var matchName = jQuery(this).attr('data-name');
        showMatchHighlight(jQuery(this).attr('data-for-user-id'), jQuery(this).attr('data-user-id'), jQuery(this).attr('data-user-role'),  function(data){
            var dialog = jQuery('#match-highlight');

            dialog.dialog('option', 'title', matchName);
            dialog.html(data);
            dialog.dialog('open');
            spinner.hide();
        }, function(data){
            spinner.hide();
        });
    }).ready(function(){
        jQuery('#match-highlight').dialog({
            autoOpen: false,
            height: jQuery(window).height() * 0.75,
            width: '50%',
            modal: true,
            buttons: {
                Close : function(){
                    jQuery(this).dialog('close');
                }
            },
            close: function() {
                jQuery(this).empty();
            }
        });
    });

    function showMatchHighlight(for_user_id, user_id, user_role, success, error){

        jQuery.ajax({
            type: "POST",
            url: "/matches/get_fields",
            data: {
                for_user_id: for_user_id,
                user_id: user_id,
                role: user_role,
            },
            success: success,
            error: error,
            dataType: "HTML"
        });
    }
}

if(affiliate) {
    jQuery(document).on('click', 'table.result tr td.affiliate[data-status=""] div', function(e){

        var div = jQuery(e.target);
        var td = div.parents('td');
        var tr = div.parents('tr');

        requestAffiliate(tr.attr('data-id'), function(data){
            div.attr('class', 'mn-'+data['status']);
            div.text(data['status'].charAt(0).toUpperCase() + data['status'].substr(1));
            td.attr('data-status', data['status']);
        });
    });

    function requestAffiliate(approver_id, success, error){

        jQuery.ajax({
            type: "POST",
            url: "/affiliate/request",
            data: {
                id: approver_id
            },
            success: success,
            error: error,
            dataType: "JSON"
        });

    }
}