[{foreach from=$var_constraints.$module_var item='field'}]
    <div style="font-weight: normal;">
        <input type="radio"
           name="confselects[[{$module_var}]]"
           value="[{$field|escape}]"
           id="contact_setting_[{$field}]"
           [{if ($confselects.$module_var==$field)}]checked[{/if}]>
        <label for="contact_setting_[{$field}]">
            [{ oxmultilang ident="SHOP_MODULE_`$module_var`_`$field`" }]
        </label>
    </div>
[{/foreach}]
