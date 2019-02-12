[{$smarty.block.parent}]

<div class="form-group">
    <div class="col-lg-offset-[{$leftCol|default:2}] col-lg-[{$rightCol|default:10}]">
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
</div>
