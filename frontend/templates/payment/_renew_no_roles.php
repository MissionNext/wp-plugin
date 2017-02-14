<?php
/**
 * @var $config
 * @var $defaults
 * @var $days_left
 * @var $total_days
 */
$first_default = current($defaults);
$period = ( $defaults && $first_default && $first_default['is_recurrent'] )?'month' : 'year';
$end_date = strtotime("+$days_left day");

$total_unused_value = 0;

foreach($defaults as $default){
    $total_unused_value += $default['left_amount'];
}
?>

<div class="block">
    <?php echo sprintf(__("NOTE: Your current subscription has %s days remaining until %s for a residual value of $%s.", \MissionNext\lib\Constants::TEXT_DOMAIN), $days_left, date("Y-m-d", $end_date), $total_unused_value) ?>
</div>

<div class="block payment-period">
    <label for="period-select"><?php echo __("Period", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></label>
    <select name="p" id="period-select">
        <option <?php if($period == 'month'): ?>selected="selected"<?php endif; ?> value="month"><?php echo __("Month", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></option>
        <option <?php if($period == 'year'): ?>selected="selected"<?php endif; ?> value="year"><?php echo __("Year", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></option>
    </select>
</div>

<div class="block payment-period payment-type">
    <label for="type-select"><?php echo __("Payment Type", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></label>
    <select name="payment_type" id="type-select">
        <option value="cc"><?php echo __("Credit card", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></option>
        <option value="echeck"><?php echo __("eCheck", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></option>
    </select>
</div>

<table class="table payment no-roles" id="payment_no_roles">
    <thead>
    <tr>
        <th></th>
        <th class="name"><?php echo __("Website", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
        <th class="period period-month">
            <?php echo __("Price for", \MissionNext\lib\Constants::TEXT_DOMAIN) . " " . __("Month", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </th>
        <th class="period period-year">
            <?php echo __("Price for", \MissionNext\lib\Constants::TEXT_DOMAIN) . " " . __("Year", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </th>
        <th>
            <?php echo __("Unused value", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($config as $app):
        $config = current($app['sub_configs']);
        if(!$config) continue;
        $checked = isset($defaults[$app['id']]);
        if ($checked) { $URL = "<a href='https://".$app['public_key'].".missionnext.org/dashboard' title='Go to your dashboard'>"; } else { unset($URL); }
		$left_amount = isset($defaults[$app['id']])?$defaults[$app['id']]['left_amount']:null;
        ?>
        <tr<?php if($left_amount !== null): ?> data-left="<?php echo $left_amount ?>"<?php endif; ?>>
            <td><input <?php if($checked) echo 'checked="checked"' ?> name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_BASIC ?>" type="checkbox"/></td>
            <td class="name"><?php echo $URL ?><?php echo $app['name'] ?></a></td>
            <td class="price price-month" data-price="<?php echo $config['price_month'] ?>">
                $<span><?php echo sprintf( "%.2f", $config['price_month']) ?></span>
            </td>
            <td class="price price-year" data-price="<?php echo $config['price_year'] ?>">
                $<span><?php echo sprintf( "%.2f", $config['price_year']) ?></span>
            </td>
            <td>
                <?php if($checked && $defaults[$app['id']]['left_amount'] > 0 ) echo '$' . sprintf( "%.2f",$defaults[$app['id']]['left_amount']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="col-sm-12 coupon-block">
    <?php renderTemplate('payment/_coupon') ?>
</div>


<div class="block form-group" id="renewal-options">
    <div class="renewal-today">
        <p>
            <?php echo __("A new plan is selected.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </p>
        <label for="renewal-today">
            <input type="radio" name="rt" value="t" id="renewal-today" checked="checked"/>
            <?php echo sprintf(__("Revise Plan Extending for 1 Year to %s", \MissionNext\lib\Constants::TEXT_DOMAIN), date("Y-m-d", strtotime("+1 year"))) ?>
        </label>
    </div>
    <?php if($days_left > 0): ?>
        <div class="renewal-keep">
            <label for="renewal-keep">
                <input type="radio" name="rt" value="k" id="renewal-keep"/>
                <?php echo sprintf(__("Reset Plan with new choice. Keep expiration: %s", \MissionNext\lib\Constants::TEXT_DOMAIN), date("Y-m-d", $end_date)) ?>
            </label>
        </div>
    <?php endif; ?>
    <div class="renewal-end">
        <p>
            <?php echo __("The selected plan is the same as the current plan.", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </p>
        <label for="renewal-end">
            <input type="radio" name="rt" value="e" id="renewal-end" readonly="readonly"/>
            <?php echo sprintf(__("Renew Plan Extending for 1 Year to %s", \MissionNext\lib\Constants::TEXT_DOMAIN), date("Y-m-d", strtotime("+1 year", $end_date))) ?>
        </label>
    </div>
    <div class="renewal-month">
        <label for="renewal-month">
            <input type="radio" name="rt" value="m" id="renewal-month" readonly="readonly"/>
            <?php echo __("All the subscriptions will  be canceled and new one created", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </label>
    </div>
</div>

<script>
    jQuery(document).on('change', "#payment_no_roles tbody td input[type='checkbox']", function(e){
        setRenewOptions();
        push_price();
    }).on('change', '#period-select', function(e){
        setPeriod(jQuery(e.target).val());
        setRenewOptions();
        push_price();
    }).on('change', '#renewal-options input[name="rt"]', function(e){
        push_price();
    }).on('ready', function(e){
        setPeriod(jQuery('#period-select').val());
        setRenewOptions();
        push_price();
    });

    function setRenewOptions(){

        var period = jQuery('#period-select').val();

        jQuery('#renewal-options > div').hide();

        if(period == 'year'){
            if(is_new_selected()){
                jQuery('#renewal-options .renewal-keep, #renewal-options .renewal-today').show();
                if(jQuery('#renewal-options .renewal-keep input:checked').length > 0){
                    jQuery('#renewal-options .renewal-keep input').prop('checked', true);
                } else {
                    jQuery('#renewal-options .renewal-today input').prop('checked', true);
                }
            } else {
                jQuery('#renewal-options .renewal-end').show().find('input').prop('checked', true);
            }

            jQuery('.payment-type').hide();
            jQuery('#type-select').val('cc');
        } else {
            jQuery('.payment-type').show();
            jQuery('#renewal-options .renewal-month').show().find('input').prop('checked', true);
        }

    }

    function setPeriod(val){
        jQuery('th.period, td.price').hide();
        jQuery('th.period.period-'+val+', td.price.price-'+val).show();
    }

    function push_price(){
        var rows = jQuery("#payment_no_roles tbody td input[type='checkbox']:checked");

        var renew_price = 0;
        var new_price = 0;
        var old_price = 0;
        var valued_sites = 0;

        var type = jQuery('#renewal-options input[name="rt"]:checked').val();
        var multiplier = <?php echo $days_left / $total_days ?>;

        jQuery.each(rows, function(key, value){
            var tr = jQuery(value).parents('tr');
            var site_price = parseInt(tr.find('td.price:visible').attr('data-price'));
            var isNew = !tr.is('[data-left]');
            var left_price = parseInt(tr.attr('data-left'));

            if( site_price > 0 ){
                valued_sites++;
            }

            switch (type) {
                case 'k' :
                    if(isNew){
                        new_price += multiplier * site_price;
                    }
                    break;
                case 't' :
                    if(isNew){
                        new_price += site_price;
                    } else {
                        renew_price += site_price - left_price;
                    }
                    break;
                case 'e' :
                    if(isNew){
                        new_price += multiplier * site_price + site_price;
                    } else {
                        renew_price += site_price;
                    }
                    break;
                case 'm' :
                    if(!isNew){
                        renew_price += site_price;
                        old_price += site_price > left_price ? left_price : site_price ;
                    } else {
                        new_price += site_price;
                    }
                    break;
            }

        });

        var left_rows = jQuery("#payment_no_roles tbody tr[data-left] td input[type='checkbox']:not(:checked)");

        jQuery.each(left_rows, function(key, value){

            old_price += parseInt(jQuery(value).parents('tr').attr('data-left'));
        });


        TotalCart.new_price = new_price;
        TotalCart.old_price = old_price;
        TotalCart.renew_price = renew_price;
        TotalCart.type = type;
        TotalCart.discount_active = valued_sites > 1;
        TotalCart.update();
    }

    function is_new_selected(){
        return jQuery("#payment_no_roles tbody tr:not([data-left]) td input[type='checkbox']:checked").length > 0;
    }

    function is_old_removed(){
        return jQuery("#payment_no_roles tbody tr[data-left] td input[type='checkbox']:not(:checked)").length > 0;
    }
</script>