<?php
/**
 * @var $config
 * @var $app_id
 */

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/payment/first_no_roles', 'payment/first_no_roles.js', array( 'jquery' ));
?>

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
        <th class="period">
            <?php echo __("Price for", \MissionNext\lib\Constants::TEXT_DOMAIN) . " " . __("Year", \MissionNext\lib\Constants::TEXT_DOMAIN) ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($config as $app):
        $config = current($app['sub_configs']);
        if(!$config) continue;
        $checked = $app['id'] == $app_id;
        ?>
        <tr>
            <td><input name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_BASIC ?>" type="checkbox" <?php if($checked) echo "checked='checked'" ?>/></td>
            <td class="name"><?php echo $app['name'] ?></td>
            <td class="price" data-price="<?php echo $config['price_year'] ?>">
                $<span><?php echo sprintf( "%.2f",$config['price_year']) ?></span>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>