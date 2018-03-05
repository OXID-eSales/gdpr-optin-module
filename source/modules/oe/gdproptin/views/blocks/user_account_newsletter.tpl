[{$smarty.block.parent}]

[{if !isset($oConfig)}]
    [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinUserRegistration')}]
    [{if $oViewConf->getActiveTheme()=='azure'}]
        </li>
        <li>
            <label for="oegdproptin_userregistration" style="width:100%;">
                <input type="checkbox" name="oegdproptin_userregistration" id="oegdproptin_userregistration" value="1">
                <span>[{oxmultilang ident="OEGDPROPTIN_USER_REGISTRATION_OPTIN"}]</span>
            </label>
            [{if $Errors.oegdproptin_userregistration}]
                [{assign var=oError value=$Errors.oegdproptin_userregistration.0}]
                </li>
                <li>
                    <div class="inlineError">[{$oError->getOxMessage()}]</div>
            [{/if}]
    [{else}]
        <div class="col-lg-9 col-lg-offset-3">
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
    [{/if}]
[{/if}]