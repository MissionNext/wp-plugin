<?php
/**
 * @var $config
 * @var $app_id
 */

?>
<table class="table payment roles" id="payment_table_roles">
    <thead>
    <tr>
        <th class="name"><?php echo __("Website", \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
        <th><?php echo __('Limited', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
        <th><?php echo __('Basic', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
        <th><?php echo __('Plus', \MissionNext\lib\Constants::TEXT_DOMAIN) ?></th>
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