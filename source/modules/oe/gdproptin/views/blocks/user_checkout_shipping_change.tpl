[{if !isset($oConfig)}]
    [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]
[{if !$deladr}]
    [{assign var="deladr"  value=$oConfig->getRequestParameter('deladr')}]
[{/if}]

[{$smarty.block.parent}]

[{if $oxcmp_user}]
    [{assign var="delivadr" value=$oxcmp_user->getSelectedAddress()}]
[{/if}]

[{if true == $oConfig->getConfigParam('blOeGdprOptinDeliveryAddress')}]
    [{if $oViewConf->getActiveTheme()=='azure'}]
        [{if $delivadr}]
            [{oxscript add="$('#showShipAddress').change( function() { $('#GdprOptinShipAddress, #shippingAddressForm').hide($(this).is(':checked'));});"}]
            [{oxscript add="$('#addressId').change(function() { $('#GdprOptinShipAddress').toggle($('#addressId').val() == -1 ); }); "}]
            [{oxscript add="$( '#userChangeShippingAddress' ).click(function(){ $('#GdprOptinShipAddress').toggle($(this).is(':not(:checked)')); document.getElementById('oegdproptin_changeDelAddress').value=1; });"}]
        [{else}]
            [{oxscript add="$('#showShipAddress').change( function() { $('#GdprOptinShipAddress').toggle($(this).is(':not(:checked)')); });"}]
        [{/if}]
    [{else}]
        [{if $delivadr}]
            [{oxscript add="$('#showShipAddress').change( function() { $('#GdprOptinShipAddress, #shippingAddressForm').hide($(this).is(':checked'));});"}]
            [{oxscript add="$( '.dd-edit-shipping-address' ).click(function(){ $('#GdprOptinShipAddress').toggle($(this).is(':not(:checked)')); document.getElementById('oegdproptin_changeDelAddress').value=1; });"}]
            [{oxscript add="$( '.dd-add-delivery-address' ).click( function(){ $('#GdprOptinShipAddress').toggle($(this).is(':not(:checked)')); });"}]
        [{else}]
            [{oxscript add="$('#showShipAddress').change( function() { $('#GdprOptinShipAddress').toggle($(this).is(':not(:checked)')); });"}]
        [{/if}]
    [{/if}]
[{/if}]