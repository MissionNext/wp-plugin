
jQuery(document).ready(function()
{
    selectCountry();
});

jQuery(document).on('change', 'select[data-key="country"]', function(e)
{
    selectCountry();
});

function selectCountry()
{
    jQuery('#loader').show();

    var select = jQuery('select[data-key="country"]');
    var name = select.val();
    var route = "/select/country";
    if (typeof userrole != 'undefined' && userrole != '' && userrole != 'undefined'){
        route = route + "/" + userrole;
    }

    jQuery.ajax
    ({
        url: route,
        type: "POST",
        dataType: "JSON",
        data:
        {
            name: name
        },
        success: function(data)
        {
            selectState(data);
            jQuery('#loader').hide();
        },
        error: function () {
            jQuery('#loader').hide();
        }
    });
}

function selectState(data)
{
    var select = jQuery('select[data-key="state"]');
    var value = select.val();

    select.find('option').remove();

    if(data)
    {
        jQuery.each(data, function(i, e)
        {
            if(e.value == value)
            {
                select.append('<option value="' + e.name + '" selected>' + e.name + '</option>');
            }
            else
            {
                select.append('<option value="' + e.name + '">' + e.name + '</option>');
            }
        });
    }
    else
    {
        select.append('<option value="-" selected></option>');
    }
}/**
 * Created by wizard on 24.12.14.
 */
