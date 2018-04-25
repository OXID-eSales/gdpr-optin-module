[{if !isset($oConfig)}]
    [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinDeliveryAddress')}]
    [{if $Errors.oegdproptin_deliveryaddress}]
        [{assign var=oError value=$Errors.oegdproptin_deliveryaddress.0}]
        [{if $oViewConf->getActiveTheme()=='azure'}]
            <div class="status error corners">
                <p>[{$oError->getOxMessage()}]</p>
            </div>
        [{else}]
            <div class="alert alert-danger">[{$oError->getOxMessage()}]</div>
            <div class="help-block"></div>
        [{/if}]
    [{/if}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinInvoiceAddress')}]
    [{if $Errors.oegdproptin_invoiceaddress}]
        [{assign var=oError value=$Errors.oegdproptin_invoiceaddress.0}]
        [{if $oViewConf->getActiveTheme()=='azure'}]
            <div class="status error corners">
                <p>[{$oError->getOxMessage()}]</p>
            </div>
        [{else}]
            <div class="alert alert-danger">[{$oError->getOxMessage()}]</div>
            <div class="help-block"></div>
        [{/if}]
    [{/if}]
[{/if}]

[{$smarty.block.parent}]
