{if $items|@count > 0}
    {cycle values="odd,even" name='foo' print=false reset=true advance = false}
    {foreach from=$items name=cms key=k item=item}
        {if !empty($item->fielddefs)}
            {foreach from=$item->fielddefs item=fielddef}
                {if $fielddef.value && $fielddef.type != 'Categories'}
                    {if $fielddef.type == 'SelectFile' || $fielddef.type == 'FileUpload'}
                        {assign var="src" value="{$fielddef->GetImagePath(true)}/{$fielddef.value}"}
                    {/if}
                {/if}
            {/foreach}
        {/if}
        {*if $foo == 'odd'}old{else}even{/if*}
        {*cycle name='foo'*}
        {*if $item@index eq 0} class="active"{/if*}
        {assign var="noimage" value="{themes_url}/royallove/images/people/grooms-1.png"}
        <img src="{if $item->fielddefs.aliasfield->value}{CGSmartImage src="{$src}" alias="std_thumbnail"}{else}{CGSmartImage src="{$noimage}" alias="std_thumbnail"}{/if}" alt="{$item->title}">
        {$item->desc|default:'--'}
        <a href="{$item->url}">more</a>
    {/foreach}
{/if}