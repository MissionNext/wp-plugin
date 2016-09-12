<?php
/**
 * @var $config
 * @var $app_id
 */
?>

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

<script>
    jQuery(document).on('change', "#payment_no_roles tbody td input[type='checkbox']", function(e){
        push_price();
    }).on('ready', function(e){
        push_price();
    });

    function push_price(){
        var rows = jQuery("#payment_no_roles tbody td input[type='checkbox']:checked");

        var price = 0;
        var valued_sites = 0;

        jQuery.each(rows, function(key, value){
            var site_price = parseInt(jQuery(value).parents('tr').find('td.price').attr('data-price'));

            if(site_price > 0){
                valued_sites++;
            }

            price += site_price;
        });

        TotalCart.renew_price = price;
        TotalCart.discount_active = valued_sites > 1;
        TotalCart.update();
    }
</script>