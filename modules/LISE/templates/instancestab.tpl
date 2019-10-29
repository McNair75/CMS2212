<script type="text/javascript">
    $(document).ready(function () {
    {*
    $('a.remove_permission').click(function (ev) {
    ev.preventDefault();
    var _hr = $(this).attr('href');
    cms_confirm('{lang('confirm_deleteusertag')|cms_escape:'javascript'}').done(function () {
    window.location.href = _hr;
    })
    });
    *}
        $('.apply_domain').click(function (e) {
            e.preventDefault();
            var module_name = $(this).attr('data-module');
            $('input[name="module_name"]').val(module_name);
            $('#filter').attr('title', module_name);
            $('#filter').dialog({
                width: '190px',
                resizable: false,
                modal: true
            });
        });

        $('button[name="resetfilter"]').click(function () {
            $('#filter').dialog('close');
        });
    })
</script>
{if count($modules) > 0}
    <div class="pagewarning">
        <h3>{$mod->ModLang('notice')}</h3>
        <p>{$mod->ModLang('installed_instances_warning')}</p>
    </div>
    <fieldset>
        <legend>{$mod->ModLang('installed_instances')}</legend>
        <table cellspacing="0" class="pagetable">
            <thead>
                <tr>
                    <th>{$mod->ModLang('instance_name')}</th>
                    <th>{$mod->ModLang('instance_friendlyname')}</th>
                    <th>{$mod->ModLang('instance_smarty')}</th>
                    <th>{$mod->ModLang('instance_version')}</th>
                    <th>{$mod->ModLang('clone_title')}</th>            	
                    <th>{$mod->ModLang('export_title')}</th>
                    <th class="pageicon">{$mod->ModLang('instance_uptodate')}</th>
                    <th class="pageicon">{$mod->ModLang('sync_languages')}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$modules item='entry'}
                    <tr class="{cycle values='row1,row2' name='summary'}">
                        <td {if $entry->module_name != 'LISESEO'}class="apply_domain"{/if} data-module="{$entry->module_name}">{$entry->module_name}</td>
                        <td>{$entry->friendlyname}</td>
                        <td>{ldelim}{$entry->module_name}{rdelim}</td>
                        <td>{$entry->version}</td>
                        <td>{$entry->clonelink}</td>
                        <td>{$entry->exportlink}</td>
                        <td>{$entry->upgradelink}</td>
                        <td class="init-ajax-toggle">{$entry->synclink}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </fieldset>
{/if}
{$startform}
<p class="pageinput">
    {$duplicate}
</p>   
{$endform}

{$startuploadform}
<fieldset>
    {$filenameinput}
    {$upload}
</fieldset>
{$enduploadform}
<div id="filter" title="{lang('apply_domain')}" style="display: none;">
    {$form_popup_start}
    <div class="hidden">
        <input type="hidden" name="module_name" value="" />
    </div>
    <div class="pageoverflow">
        <p class="pagetext"><label for="filter_source">Description:</label></p>
        <p class="pageinput">
            <textarea id="description" rows="3" type="text" name="mod_description" placeholder="LISE allows you to create lists that you can display throughout your website."/></textarea>
        </p>
    </div>
    <div class="pageoverflow">
        <p class="pagetext"><label for="filter_source">Domains:</label></p>
        <p class="pageinput">
            <select id="filter_source" name="domain_id[]" multiple="multiple" size="{$domain_sections|count}">
                {html_options options=$domain_sections}
            </select>
        </p>
    </div>
    <div class="pageoverflow">
        <p class="pageinput">
            <input type="submit" name="submitfilter" value="{lang('submit')}"/>
            <input type="submit" name="resetfilter" value="{lang('cancel')}"/>
        </p>
    </div>
    {$form_popup_end}
</div>