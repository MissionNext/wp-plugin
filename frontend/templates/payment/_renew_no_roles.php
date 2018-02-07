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

        if(!$config)
            continue;

        $checked = isset($defaults[$app['id']]);
        if ($checked) {
            $URL = "<a href='https://".$app['public_key'].".missionnext.org/dashboard' title='Go to your dashboard'>";
        } else {
            unset($URL);
        }

		$left_amount = isset($defaults[$app['id']])?$defaults[$app['id']]['left_amount']:null;
        ?>
        <tr<?php if($left_amount !== null): ?> data-left="<?php echo $left_amount ?>"<?php endif; ?>>
            <td><input <?php if($checked) echo 'checked="checked"' ?> name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_BASIC ?>" type="checkbox"/></td>
            <td class="name">
                <?php if (isset($URL)) {
                    echo $URL;
                    echo $app['name'];
                    echo "</a>";
                } else {
                    echo $app['name'];
                } ?>
            </td>
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
    var multiplier = '<?php echo $days_left / $total_days ?>';
</script>

<?php
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/payment/renew_no_roles', 'payment/renew_no_roles.js', array( 'jquery' ));
?>