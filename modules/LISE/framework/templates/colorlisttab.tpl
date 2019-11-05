{if isset($activelang)}{$langList}<br/><br/>{/if}
{if count($items) > 0}
    <table id="colorlist" cellspacing="0" class="pagetable">
        <thead>
            <tr>
                <th class="pageicon">{$mod->ModLang('icon')}</th>
                <th>{$mod->ModLang('color_name')}</th>
                <th>{$mod->ModLang('color_code')}</th>
                <th data-ignore="highlight" data-hide="phone,tablet" data-sort-ignore="true" data-class="hide-heading"{$mod->ModLang('alias')}</th>
                <th class="pageicon">{$mod->ModLang('set_popular')}</th>
                <th class="pageicon">&nbsp;</th>
                <th data-ignore="highlight" data-hide="phone,tablet" data-sort-ignore="true" data-class="hide-heading" class="pageicon">&nbsp;</th>
                <th class="pageicon"  data-sort-ignore="true" >&nbsp;</th>
                <th title="{$mod->ModLang('select_all')}" class="pageicon no-sort"  data-sort-ignore="true" ><input id="check_all_category" type="checkbox" /></th>
            </tr>
        </thead>
        <tbody class="content" width="100%">
            {foreach from=$items item=entry}
                {cycle values="row1,row2" assign='rowclass'}
                <tr id="color_{$entry->color_id}" class="{$rowclass}">
                    <td></td>
                    <td>{repeat string='>&nbsp;' times=$entry->depth - 1}{$entry->name}</td>
                    <td><div style="{if $entry->icon}background: transparent url('../uploads/{$entry->icon}'){else}background-color:{$entry->code|default:$entry->alias}{/if}; width:50px; height:20px; border:1px solid #ccc;">&nbsp;</div></td>
                    <td>{$entry->alias}</td>
                    <td class="init-ajax-toggle approve-color">{$entry->popular}</td>
                    <td class="init-ajax-toggle approve-color">{$entry->approve}</td>
                    <td>{$entry->editlink}</td>
                    <td class="init-ajax-delete">{$entry->delete}</td>
                    <td class="color-mass-action">{$entry->select}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <div class="pageshowrows">&nbsp;</div>
    <div class="pageoptions" style="float:right;">
        <select id="lise_color_mass_action">
            <option value="">{$mod->ModLang('select_one')}</option>
            <option value="delete">Delete</option>
            <option value="approve">Toggle active</option>
        </select>
    </div>
{/if}

<div class="pageoptions"> {$addlink} {if isset($reorderlink)}&nbsp;{$reorderlink}{/if}</div>