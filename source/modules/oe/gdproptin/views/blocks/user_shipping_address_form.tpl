[{$smarty.block.parent}]

[{if !isset($oConfig)}]
    [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinDeliveryAddress')}]
    [{if $oViewConf->getActiveTheme()=='azure'}]
        <p id="GdprShippingAddressOptin" style="[{if $delivadr || !$oView->showShipAddress()}]display: none;[{/if}]">
            <input type="hidden" id="oegdproptin_changeDelAddress" name="oegdproptin_changeDelAddress" value="0">
            <input type="checkbox" name="oegdproptin_deliveryaddress" id="oegdproptin_deliveryaddress" value="1">
            <label for="oegdproptin_deliveryaddress"><strong>[{oxmultilang ident="OEGDPROPTIN_STORE_DELIVERY_ADDRESS"}]</strong></label>
            <div id="oegdproptin_deliveryaddress_error" style="display:none;" class="inlineError">[{oxmultilang ident="OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS" }]</div>
        </p>
    [{else}]
        <div class="clearfix"></div>
        <div id="GdprShippingAddressOptin" class="form-group" style="[{if $delivadr || !$oView->showShipAddress()}]display: none;[{/if}]">
            <div class="col-lg-12">
                <div class="checkbox">
                    <label class="req">
                        <input type="hidden" class="hidden" id="oegdproptin_changeDelAddress" name="oegdproptin_changeDelAddress" value="0">
                        <input type="checkbox" name="oegdproptin_deliveryaddress" id="oegdproptin_deliveryaddress" value="1" >
                        <strong>[{oxmultilang ident="OEGDPROPTIN_STORE_DELIVERY_ADDRESS"}]</strong>
                    </label>
                </div>
                <div id="oegdproptin_deliveryaddress_error" style="display:none;" class="text-danger">[{oxmultilang ident="OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS" }]</div>
            </div>
        </div>
    [{/if}]
[{/if}]
