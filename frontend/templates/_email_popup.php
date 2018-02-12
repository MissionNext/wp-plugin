<div id="email-popup" style="display: none">
    <div class="field">
        <label for="email-to"><?php echo __('To', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
        <input id="email-to" type="text" disabled="disabled"/>
    </div>
    <div class="field">
        <label for="email-from"><?php echo __('From', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
        <input id="email-from" type="text" disabled="disabled"/>
    </div>
    <div class="field">
        <label for="email-subject"><?php echo __('Subject', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
        <input id="email-subject" type="text"/>
    </div>
    <div class="field">
        <label for="email-body"><?php echo __('Body', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
        <textarea id="email-body" cols="30" rows="10"></textarea>
    </div>
</div>