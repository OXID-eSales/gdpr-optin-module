[{include file="page/info/contact.tpl" assign="pageContent"}]

[{if $oViewConf->getActiveTheme()=='azure'}]
    [{capture name="optIn" assign="optIn"}]
        [{if $oView->isOptInValidationRequired()}]
            </li>
            <li>
                <label for="c_oegdproptin" style="width: 100%;">
                    <input type="hidden" name="c_oegdproptin" value="0">
                    <input type="checkbox"
                           name="c_oegdproptin"
                           id="c_oegdproptin"
                           value="1">
                    <strong>[{oxmultilang ident="OEGDPROPTIN_CONTACT_FORM_MESSAGE_STATISTICAL" }]</strong>
                </label>
            [{if $oView->isOptInError()}]
                </li>
                <li>
                    <div class="inlineError">[{oxmultilang ident="OEGDPROPTIN_CONTACT_FORM_ERROR_MESSAGE" }]</div>
            [{/if}]
        [{else}]
            </li>
            <li>
                [{oxmultilang ident="OEGDPROPTIN_CONTACT_FORM_MESSAGE_DELETION"}]
        [{/if}]
    [{/capture}]
[{else}]
    [{capture name="optIn" assign="optIn"}]
        <div style="margin-top: 20px;">
            [{if $oView->isOptInValidationRequired()}]
                <label for="c_oegdproptin">
                    <input type="hidden" name="c_oegdproptin" value="0">
                    <input type="checkbox"
                           name="c_oegdproptin"
                           id="c_oegdproptin"
                           value="1">
                    <strong>[{oxmultilang ident="OEGDPROPTIN_CONTACT_FORM_MESSAGE_STATISTICAL" }]</strong>
                </label>
                [{if $oView->isOptInError()}]
                    <div class="text-danger">[{oxmultilang ident="OEGDPROPTIN_CONTACT_FORM_ERROR_MESSAGE" }]</div>
                [{/if}]
            [{else}]
                [{oxmultilang ident="OEGDPROPTIN_CONTACT_FORM_MESSAGE_DELETION"}]
            [{/if}]
        </div>
    [{/capture}]
[{/if}]

[{assign var=marker value="/<textarea.*?name=\"c_message\".*?>.*?<\/textarea>/"}]
[{assign var=replacement value='$0'|cat:$optIn}]
[{$pageContent|regex_replace:$marker:$replacement}]