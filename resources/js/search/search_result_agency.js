function OpenInNewTab(url) {
    var win = window.open(url, '_blank');
    win.focus();
}

jQuery(document).on('click', 'table.result tr td.note div', function(e){

        var tr = jQuery(e.target).parents('tr');

        openNote(
            tr.data('id'),
            jQuery(e.target).parents('td').attr('data-note'),
            jQuery(e.target).parents('td').attr('data-notes'),
            tr.attr('data-name'),
            jQuery(e.target).parents('td').attr('data-group')
        );
    }
).on('change', '.affiliate-organization', function(e){
    var selected_org = jQuery(this).val();
    jQuery('.result').hide();

    if (current_org > 0) {
        jQuery('#orgid-' + selected_org).show();
    }
}).ready(function(){
    jQuery('.result').hide();

    if (current_org > 0) {
        jQuery('#orgid-' + current_org).show();
    }


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
});

function openNote(id, text, notes, name, folder){

    var modal = jQuery('#note');

    modal.find('[name="id"]').val(id);
    modal.find('textarea.message').val(text?text:' ');
    modal.find('#other_notes').html('');
    if (notes != 'null') {
        var notes_html = '';
        var notes_array = JSON.parse(notes);
        notes_html += "<h5>" + notes_array.org_name + "</h5>";
        notes_html += "<p>" + notes_array.note_text + "</p>";
        notes_html += '<br />';
        modal.find('#other_notes').html(notes_html);
    }

    modal.find('.help .name').html(name);
    modal.find('.help .folder span').html(folder);

    modal.dialog('open');
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
