[{$smarty.block.parent}]

[{if !isset($oConfig)}]
  [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinDeliveryAddress')}]
  [{if $oViewConf->getActiveTheme()=='azure'}]
    <p id="GdprOptinShipAddress" style="[{if $delivadr || !$oView->showShipAddress()}]display: none;[{/if}]">
      <input type="hidden" class="hidden" id="oegdproptin_changeDelAddress" name="oegdproptin_changeDelAddress" value="0">
      <input type="checkbox" name="oegdproptin_deliveryaddress" id="oegdproptin_deliveryaddress" value="1">
      <label for="oegdproptin_deliveryaddress"><strong>[{oxmultilang ident="OEGDPROPTIN_STORE_DELIVERY_ADDRESS"}]<strong></label>
      <div id="oegdproptin_deliveryaddress_error" style="display:none;" class="inlineError">[{oxmultilang ident="OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN" }]</div>
  </p>
  [{else}]
    <div class="form-group[{if $Errors.oegdproptin_deliveryaddress}] oxInValid[{/if}]" id="GdprOptinShipAddress" class="checkbox" style="display: none;">
      <div class="col-lg-9 col-lg-offset-3">
        <div class="checkbox">
          <label for="oegdproptin_deliveryaddress">
            <input type="hidden" class="hidden" id="oegdproptin_changeDelAddress" name="oegdproptin_changeDelAddress" value="0">
            <input type="checkbox" name="oegdproptin_deliveryaddress" id="oegdproptin_deliveryaddress" value="1"> <strong>[{oxmultilang ident="OEGDPROPTIN_STORE_DELIVERY_ADDRESS"}]<strong>
          </label>
        </div>
      </div>
      <div class="col-lg-9 col-lg-offset-3">
         <div id="oegdproptin_deliveryaddress_error" style="display:none;" class="text-danger">[{oxmultilang ident="OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN" }]</div>
         <div class="help-block"></div>
      </div>
    </div>
  [{/if}]
[{/if}]
