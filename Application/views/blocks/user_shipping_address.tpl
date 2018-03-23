[{$smarty.block.parent}]

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
