<div id="email-popup" style="display: none">
    <input id="to" type="hidden" disabled="disabled"/>
    <input id="from" type="hidden" disabled="disabled"/>
    <div class="field">
        <label for="name-to"><?php echo __('To', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
        <input id="name-to" type="text" disabled="disabled"/>
    </div>
    <div class="field field-from">
        <div class="block-from">
            <label for="name-from"><?php echo __('From', \MissionNext\lib\Constants::TEXT_DOMAIN)?>:</label>
            <input id="name-from" type="text" disabled="disabled"/>
        </div>
        <div class="block-cc">
            <input type="checkbox" id="cc_me" name="cc_me" value="copy" checked />
            <label for="cc_me"><?php echo __('Copy Me', \MissionNext\lib\Constants::TEXT_DOMAIN)?></label>
        </div>
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