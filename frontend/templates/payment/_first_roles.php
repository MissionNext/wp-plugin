<?php
/**
 * @var $config
 * @var $app_id
 */

?>

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
        <th><?php echo __('Tier One', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th> <!-- limited -->
        <th><?php echo __('Tier Two', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th> <!-- basic -->
        <th><?php echo __('Tier Three', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th> <!-- plus -->
        <th><?php echo __('None', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($config as $app):

        $checked = $app['id'] == $app_id;
        ?>
        <tr>
            <td class="name"><?php echo $app['name'] ?></td>

            <?php if(isset($app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_LIMITED])): ?>
                <td data-price="<?php echo $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_LIMITED]['price_year']?>">
                    <input name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_LIMITED ?>" type="radio"/>
                    <span>$<?php echo sprintf( "%.2f", $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_LIMITED]['price_year']) ?></span>
                </td>
            <?php else: ?>
                <td>-</td>
            <?php endif; ?>

            <?php if(isset($app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_BASIC])): ?>
                <td data-price="<?php echo $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_BASIC]['price_year']?>">
                    <input name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_BASIC ?>" type="radio" <?php if($checked) echo "checked='checked'" ?>/>
                    <span>$<?php echo sprintf( "%.2f", $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_BASIC]['price_year']) ?></span>
                </td>
            <?php else: ?>
                <td>-</td>
            <?php endif; ?>

            <?php if(isset($app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_PLUS])): ?>
                <td data-price="<?php echo $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_PLUS]['price_year']?>">
                    <input name="a[<?php echo $app['id'] ?>]" value="<?php echo \MissionNext\lib\Constants::PARTNERSHIP_PLUS ?>" type="radio"/>
                    <span>$<?php echo sprintf( "%.2f", $app['sub_configs'][\MissionNext\lib\Constants::PARTNERSHIP_PLUS]['price_year']) ?></span>
                </td>
            <?php else: ?>
                <td>-</td>
            <?php endif; ?>
            <td><input <?php if(!$checked): ?>checked="checked"<?php endif; ?> name="a[<?php echo $app['id'] ?>]" value="none" type="radio"/></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
Partnership Notes (<a href="https://new.missionnext.org/welcome/for-organizations/#partnership-fees" title="Fee Schedule" target="_blank">ExploreNext Tier Pricing</a>):<ul>
<li>Tier 3: Annual revenues of $20 million or more
<li>Tier 2: Annual revenues of $5 - $20 million
<li>Tier 1: Annual revenues of under $5 million 
<li>One Rate: All TeachNext Schools 
</ul>
<script>

    jQuery(document).on('change', '#payment_table_roles input[type="radio"]', function(e){
        pushPrice();
    }).on('ready', function(e){
        pushPrice();
    });

    function pushPrice(){

        var rows = jQuery("#payment_table_roles input[type='radio']:not([value='none']):checked");

        var price = 0;
        var sites = 0;

        jQuery.each(rows, function(key, value){

            var site_price = parseInt(jQuery(value).parents('td').attr('data-price'));

            if(site_price){
                price += site_price;
                sites++;
            }

        });

        TotalCart.renew_price = price;
        TotalCart.discount_active = sites > 1;
        TotalCart.update();

    }

</script>