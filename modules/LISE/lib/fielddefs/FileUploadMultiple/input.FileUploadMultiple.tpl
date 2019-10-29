<div class="pageoverflow">
    <p class="pagetext">{$fielddef->GetName()}{if $fielddef->IsRequired()}*{/if}:</p>
    <p class="pageinput">
        {if $fielddef->GetDesc()}({$fielddef->GetDesc()})<br />{/if}
        
        {*if $fielddef->GetOptionValue('image')}{$fielddef->RenderForAdminListing($actionid, $returnid)}{/if*}
        {*<input type="hidden" name="{$actionid}customfield[{$fielddef->GetId()}][]" value="{$fielddef->GetValue()}" />*}
        <input type="file" name="{$actionid}customfield[{$fielddef->GetId()}][]" size="{$fielddef->GetOptionValue('size')}" multiple>{if !$fielddef->IsRequired() && $fielddef->HasValue()}
        {assign var="abc" value=","|explode:$fielddef->GetValue()}
        {if $fielddef->HasValue()}
        {foreach from=$abc key=lipit item=foo}
            <br /><input type="checkbox" name="{$actionid}delete_customfield[{$fielddef->GetId()}][{$lipit}]" value="delete" title="{$mod->ModLang('delete')}" />
            <em>{$mod->ModLang('value')}: {$foo}</em>
        {/foreach}
        {/if}
        {/if}
        </p>
    </div>