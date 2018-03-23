[{capture name="optInJs" assign="optInJs"}]
    $("#rvw_oegdproptin").parents("form").first().submit(function(event){
        event.preventDefault();
        $("#rvw_oegdproptin_error").addClass("hidden");

        if ($("#rvw_oegdproptin:checked").length === 1) {
            this.submit();
        } else {
            $("#rvw_oegdproptin_error").removeClass("hidden");
        }
    });
[{/capture}]

[{capture name="optIn" assign="optIn"}]
    [{if $oView->isReviewOptInValidationRequired()}]
        <div style="margin-top:10px;" class="rvw_oegdproptin_block">
            <input type="hidden" name="rvw_oegdproptin" value="0">
            <label for="rvw_oegdproptin">
                <input type="checkbox"
                       name="rvw_oegdproptin"
                       id="rvw_oegdproptin"
                       value="1">
                <strong>[{oxmultilang ident="OEGDPROPTIN_REVIEW_FORM_MESSAGE" }]</strong>
            </label>

            <div id="rvw_oegdproptin_error"
                 class="[{if !$oView->isReviewOptInError()}]hidden [{/if}] text-danger">
                    [{oxmultilang ident="OEGDPROPTIN_REVIEW_FORM_ERROR_MESSAGE" }]
            </div>
        </div>

        [{oxscript add=$optInJs}]
    [{/if}]
[{/capture}]

[{$smarty.block.parent}]
[{$smarty.capture.optIn}]
