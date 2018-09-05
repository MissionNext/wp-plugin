<?php
/**
 * @var $config
 * @var $defaults
 * @var $days_left
 * @var $total_days
 */
//  echo "roles: \$config = $config<br>"; print_r($config);
//  echo "<br>\$defaults = $defaults<br>"; print_r($defaults);
$first_default = current($defaults);
$period = ( $defaults && $first_default && $first_default['is_recurrent'] )?'month' : 'year';
$end_date = strtotime("+$days_left day");

$total_unused_value = 0;

foreach($defaults as $default){
    $total_unused_value += $default['left_amount'];
}

?>

<div class="block bg-success">
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

<table class="table payment roles" id="payment_table_roles">
    <thead>
    <tr>
        <th class="name"><?php echo __("Website", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
        <th><?php echo __('Tier One', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th> <!--limited -->
        <th><?php echo __('Tier Two', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th> <!-- basic -->
        <th><?php echo __('Tier Three', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th> <!-- plus -->
        <th><?php echo __('None', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
        <th><?php echo __("Unused value", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($config as $app):
        $left_amount = isset($defaults[$app['id']])?$defaults[$app['id']]['left_amount']:null;
        ?>
        <tr <?php if($left_amount): ?>data-left="<?php echo $left_amount ?>"<?php endif; ?>>
            <td class="name"><?php echo $app['name'] ?></td>

            <?php if(isset($app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_LIMITED])): ?>
                <td data-price="<?php echo $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_LIMITED]['price_year']?>" data-period="year" data-partnership="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_LIMITED ?>">
                    <input name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_LIMITED; ?>" type="radio" <?php if( isset($defaults[$app['id']]) && !$defaults[$app['id']]['is_recurrent'] && $defaults[$app['id']]['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_LIMITED ) echo 'checked="checked"'?>/>
                    <span>$<?php echo sprintf( "%.2f", $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_LIMITED]['price_year']) ?></span>
                </td>
                <td data-price="<?php echo $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_LIMITED]['price_month']?>" data-period="month" data-partnership="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_LIMITED ?>">
                    <input name="a[<?php echo $app['id'] ?>]" value="none" type="radio" <?php if( isset($defaults[$app['id']]) && $defaults[$app['id']]['is_recurrent'] && $defaults[$app['id']]['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_LIMITED ) echo 'checked="checked"'?>/>
                    <span>$<?php echo sprintf( "%.2f", $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_LIMITED]['price_month']) ?></span>
                </td> 
            <?php else: ?>
                <td data-period="year">-</td>
                <td data-period="month">-</td>
            <?php endif; ?>

            <?php if(isset($app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_BASIC])): ?>
                <td data-price="<?php echo $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_BASIC]['price_year']?>" data-period="year" data-partnership="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_BASIC ?>">
                    <input name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_BASIC ?>" type="radio" <?php if( isset($defaults[$app['id']]) && !$defaults[$app['id']]['is_recurrent'] && $defaults[$app['id']]['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_BASIC ) echo 'checked="checked"'?>/>
                    <span>$<?php echo sprintf( "%.2f", $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_BASIC]['price_year']) ?></span>
                </td>
                <td data-price="<?php echo $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_BASIC]['price_month']?>" data-period="month" data-partnership="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_BASIC ?>">
                    <input name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_BASIC ?>" type="radio" <?php if( isset($defaults[$app['id']]) && $defaults[$app['id']]['is_recurrent'] && $defaults[$app['id']]['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_BASIC ) echo 'checked="checked"'?>/>
                    <span>$<?php echo sprintf( "%.2f", $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_BASIC]['price_month']) ?></span>
                </td>
            <?php else: ?>
                <td data-period="year">-</td>
                <td data-period="month">-</td>
            <?php endif; ?>

            <?php if(isset($app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_PLUS])): ?>
                <td data-price="<?php echo $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_PLUS]['price_year']?>" data-period="year" data-partnership="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_PLUS ?>">
                    <input name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_PLUS ?>" type="radio" <?php if( isset($defaults[$app['id']]) && !$defaults[$app['id']]['is_recurrent'] && $defaults[$app['id']]['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_PLUS ) echo 'checked="checked"'?>/>
                    <span>$<?php echo sprintf( "%.2f", $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_PLUS]['price_year']) ?></span>
                </td>
                <td data-price="<?php echo $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_PLUS]['price_month']?>" data-period="month" data-partnership="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_PLUS ?>">
                    <input name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_PLUS ?>" type="radio" <?php if( isset($defaults[$app['id']]) && $defaults[$app['id']]['is_recurrent'] && $defaults[$app['id']]['partnership'] == \MissionNext\lib\Constants::PARTNERSHIP_PLUS ) echo 'checked="checked"'?>/>
                    <span>$<?php echo sprintf( "%.2f", $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_PLUS]['price_month']) ?></span>
                </td>
            <?php else: ?>
                <td data-period="year">-</td>
                <td data-period="month">-</td>
            <?php endif; ?>
            <td><input <?php if(!isset($defaults[$app['id']])) echo "checked='checked'" ?> name="a[<?php echo $app['id'] ?>]" value="none" type="radio"/></td>
            <td><?php if(isset($defaults[$app['id']]) && $defaults[$app['id']]['left_amount'] > 0 ) echo '$' . sprintf( "%.2f", $defaults[$app['id']]['left_amount']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
Partnership Notes (<a href="https://missionfinder.net/welcome/for-organizations/#partnership-fees" title="Fee Schedule" target="_blank">ExploreNext Tier Pricing</a>):<ul>
<li>Tier 1: Annual revenues of under $5 million 
<li>Tier 2: Annual revenues of $5 - $20 million 
<li>Tier 3: Annual revenues of $20 million or more
<li>One Rate: All TeachNext Schools 
</ul>
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
    var multiplier = '<?php echo $total_days > 0 ? $days_left / $total_days: 1 ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/payment/renew_roles', 'payment/renew_roles.js', array( 'jquery' ), false, true);
?>
