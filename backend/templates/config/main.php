<?php
/**
 * @var $options
 * @var $connected
 */
?>

<style>
</style>

<form action="<?php echo $_SERVER[ 'REQUEST_URI' ] ?>" method="post">

    <table class="form-table">
        <tbody>
            <tr>
                <th>Api Connection Config</th>
            </tr>
            <tr>
                <td><label for="config_app_id">App ID</label></td>
                <td><input id="config_app_id" type="text" name="config[public_key]" value="<?php echo isset($options[\MissionNext\lib\Constants::PUBLIC_KEY_TOKEN])?$options[\MissionNext\lib\Constants::PUBLIC_KEY_TOKEN]:'' ?>"/></td>
            </tr>
            <tr>
                <td><label for="config_key">Private Key</label></td>
                <td><input id="config_key" type="password" name="config[private_key]" value="<?php echo isset($options[\MissionNext\lib\Constants::PRIVATE_KEY_TOKEN])?$options[\MissionNext\lib\Constants::PRIVATE_KEY_TOKEN]:'' ?>"/></td>
            </tr>
            <?php if($connected): ?>
            <tr>
                <td>Options</td>
            </tr>
            <tr>
                <th><label for="config_agency_trigger">Service Organization present?</label></th>
                <td><input id="config_agency_trigger" type="checkbox" name="config[agency_trigger]" value="1" <?php echo ( isset($options[\MissionNext\lib\Constants::CONFIG_AGENCY_TRIGGER]) && $options[\MissionNext\lib\Constants::CONFIG_AGENCY_TRIGGER])?'checked="checked"':'' ?>"/></td>
            </tr>
            <tr>
                <th><label for="block_website_trigger">Block website on dashboard?</label></th>
                <td><input id="block_website_trigger" type="checkbox" name="config[block_website]" value="1" <?php echo ( isset($options[\MissionNext\lib\Constants::CONFIG_BLOCK_WEBSITE]) && $options[\MissionNext\lib\Constants::CONFIG_BLOCK_WEBSITE])?'checked="checked"':'' ?>"/></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p class="submit">
        <button class="button button-primary" id="submit" name="submit" type="submit">Save</button>
    </p>
</form>