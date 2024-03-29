{if $modules|count > 0}
    <div class="pagewarning">
        <h3>{$mod->ModLang('notice')}</h3>
        <p>{$mod->ModLang('installed_instances_warning')}</p>
    </div>
    <fieldset>
        <legend>{$mod->ModLang('installed_modules')}</legend>
        <table cellspacing="0" class="pagetable">
            <thead>
                <tr>
                    <th>{$mod->ModLang('instance_name')}</th>
                    <th>{$mod->ModLang('instance_friendlyname')}</th>
                    <th>{$mod->ModLang('instance_smarty')}</th>
                    <th>{$mod->ModLang('instance_version')}</th>
                    <th class="pageicon">{$mod->ModLang('instance_uptodate')}</th>
                    <th class="pageicon">{$mod->ModLang('sync_languages')}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$modules item='entry'}
                    <tr class="{cycle values='row1,row2' name='summary'}">
                        <td>{$entry->module_name}</td>
                        <td>{$entry->friendlyname}</td>
                        <td>{ldelim}{$entry->module_name}{rdelim}</td>
                        <td>{$entry->version}</td>
                        <td>{$entry->upgradelink}</td>
                        <td class="init-ajax-toggle">{$entry->synclink}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </fieldset>
{/if}
{$startform}
<div class="pageoverflow">
    <p class="pageinput">
        {$duplicate_table|default:''}
        {$scan_modules}
    </p>
</div>
{$endform}