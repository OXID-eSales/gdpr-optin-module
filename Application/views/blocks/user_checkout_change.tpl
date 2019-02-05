[{if !isset($oConfig)}]
    [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{$smarty.block.parent}]

[{if $oxcmp_user}]
    [{assign var="delivadr" value=$oxcmp_user->getSelectedAddress()}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinDeliveryAddress')}]
    [{if $delivadr}]
        [{oxscript add="function toggleGdprOptinShipAddress() { $('#GdprOptinShipAddress, #shippingAddressForm').hide($(this).is(':checked'));}"}]
        [{oxscript add="$('#showShipAddress').change(toggleGdprOptinShipAddress);"}]
        [{oxscript add="toggleGdprOptinShipAddress.bind($('#showShipAddress')[0])();"}]
        [{oxscript add="$( '.dd-edit-shipping-address' ).click(function(){ $('#GdprOptinShipAddress').toggle($(this).is(':not(:checked)')); document.getElementById('oegdproptin_changeDelAddress').value=1; });"}]
        [{oxscript add="$( '.dd-add-delivery-address' ).click( function(){ $('#GdprOptinShipAddress').toggle($(this).is(':not(:checked)')); });"}]
    [{else}]
        [{oxscript add="function toggleGdprOptinShipAddress() { $('#GdprOptinShipAddress').toggle($(this).is(':not(:checked)'));}"}]
        [{oxscript add="toggleGdprOptinShipAddress.bind($('#showShipAddress')[0])();"}]
        [{oxscript add="$('#showShipAddress').change(toggleGdprOptinShipAddress);"}]
    [{/if}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinInvoiceAddress')}]
    [{oxscript add="$('#userChangeAddress').click( function() { $('#GdprInvoiceAddressOptin').toggle();return false;});"}]
[{/if}]

[{if !isset($oConfig)}]
  [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{capture assign="optinValidationJS"}]
    [{strip}]
        $("#userNextStepBottom, #userNextStepTop").click(function(event){
            $("#oegdproptin_deliveryaddress_error").hide();
            $("#oegdproptin_invoiceaddress_error").hide();

            var success = true;
            if ( $('#oegdproptin_invoiceaddress').is(':visible') && $('#oegdproptin_invoiceaddress').is(':not(:checked)') )
            {
                event.preventDefault();
                $("#oegdproptin_invoiceaddress_error").show();
            }
            if ($('#oegdproptin_deliveryaddress').is(':visible') && $('#oegdproptin_deliveryaddress').is(':not(:checked)'))
            {
                event.preventDefault();
                $("#oegdproptin_deliveryaddress_error").show();
            }

            $(this).submit();
            if (!success){
                return false;
            }
        });
    [{/strip}]
[{/capture}]

[{oxscript add=$optinValidationJS}]
