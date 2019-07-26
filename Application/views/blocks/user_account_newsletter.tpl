[{$smarty.block.parent}]

[{if !isset($oConfig)}]
    [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinUserRegistration')}]
<div class="form-group row">
    <div class="col-lg-9 col-lg-offset-3 offset-lg-3">
        <div class="checkbox">
            <label for="oegdproptin_userregistration">
                <input type="checkbox" name="oegdproptin_userregistration" id="oegdproptin_userregistration" value="1"> [{oxmultilang ident="OEGDPROPTIN_USER_REGISTRATION_OPTIN"}]
            </label>
        </div>
        [{if $Errors.oegdproptin_userregistration}]
            [{assign var=oError value=$Errors.oegdproptin_userregistration.0}]
            <div class="text-danger">[{$oError->getOxMessage()}]</div>
        [{/if}]
    </div>
</div>
[{/if}]