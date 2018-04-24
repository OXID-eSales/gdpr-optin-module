[{if !isset($oConfig)}]
    [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{if $oxcmp_user}]
    [{assign var="delivadr" value=$oxcmp_user->getSelectedAddress()}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinDeliveryAddress')}]
    [{if $delivadr}]
        [{oxscript add="$('#showShipAddress').change( function() { $('#GdprShippingAddressOptin, #shippingAddressForm').hide($(this).is(':checked'));});"}]
        [{oxscript add="$('.dd-edit-shipping-address').click(function(){ $('#GdprShippingAddressOptin').toggle($(this).is(':not(:checked)')); document.getElementById('oegdproptin_changeDelAddress').value=1; });"}]
        [{oxscript add="$('.dd-add-delivery-address').click( function(){ $('#GdprShippingAddressOptin').toggle($(this).is(':not(:checked)')); });"}]
    [{else}]
        [{oxscript add="$('#showShipAddress').change( function() { $('#GdprShippingAddressOptin').toggle($(this).is(':not(:checked)')); });"}]
    [{/if}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinInvoiceAddress')}]
    [{oxscript add="$('#userChangeAddress').click( function() { $('#GdprInvoiceAddressOptin').show();$('#userChangeAddress').hide();return false;});"}]
[{/if}]

[{capture assign="optinValidationJS"}]
    [{strip}]
        $("#accUserSaveTop").click(function(event){
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

[{$smarty.block.parent}]