[{$smarty.block.parent}]

[{if !isset($oConfig)}]
  [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinInvoiceAddress')}]
  <div class="form-group[{if $Errors.oegdproptin_invoiceaddress}] oxInValid[{/if}]" id="GdprInvoiceAddressOptin" style="display: none;">
    <div class="col-lg-9 col-lg-offset-3 offset-lg-3">
      <div class="checkbox">
        <label for="oegdproptin_invoiceaddress">
          <input type="hidden" class="hidden" id="oegdproptin_changeInvAddress" name="oegdproptin_changeInvAddress" value="0">
          <input type="checkbox" name="oegdproptin_invoiceaddress" id="oegdproptin_invoiceaddress" value="1"> <strong>[{oxmultilang ident="OEGDPROPTIN_STORE_INVOICE_ADDRESS"}]</strong>
        </label>
      </div>
    </div>
    <div class="col-lg-9 col-lg-offset-3 offset-lg-3">
       <div id="oegdproptin_invoiceaddress_error" style="display:none;" class="text-danger">[{oxmultilang ident="OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS" }]</div>
       <div class="help-block"></div>
    </div>
  </div>
[{/if}]
