<!-- start tab -->
<div id="page_tabs">
    <div id="editcolor">
        {$title}
    </div>
</div>
<!-- end tab //-->
<!-- start content -->
<div id="page_content"> 
    <div id="editcolor_c"> 
        {$backlink}
        {$startform}

        <div class="pageoverflow">
            <p class="pagetext">*{$mod->ModLang('color_name')}:</p>
            <p class="pageinput">{$input_color}</p>
        </div>
        <div class="pageoverflow">
            <p class="pagetext">{$mod->ModLang('color_code')}:</p>
            <p class="pageinput">{$input_code}</p>
        </div>
        <div class="pageoverflow">
            <p class="pagetext">{$mod->ModLang('alias')}:</p>
            <p class="pageinput">{$input_alias}</p>
        </div>
        {if $color_noparent}
            <div class="pageoverflow">
                <p class="pagetext">{lang('parent')}:</p>
                <p class="pageinput">{$input_parent}</p>
            </div>
        {/if}

        <div class="pageoverflow">
            <p class="pagetext">{$mod->ModLang('icon_upload')}:</p>
            <p class="pageinput">

                {if isset($icon_upload) && !empty($icon_upload) && $icon_upload != ''}<img src="{uploads_url}/{$icon_upload}" alt="Missing::Icon" style="max-width:100px; margin-top:10px; margin-bottom:10px;"/><br/>
                    {$mod->ModLang('delete')}:<input type="checkbox" name="{$actionid}deleteimg" value="{$icon_upload}" /><br/>
                {/if}
                <input type="file" name="{$actionid}icon_upload" size="50" maxlength="255" />
            </p>
        </div>
        {*
        <div class="pageoverflow">
            <p class="pagetext">{$mod->ModLang('picture_upload')}:</p>
            <p class="pageinput">

                {if isset($picture_upload) && !empty($picture_upload) && $picture_upload != ''}{$url_photo}<br/>
                    {$mod->ModLang('delete')}:<input type="checkbox" name="{$actionid}picture_deleteimg" value="{$picture_upload}" /><br/>
                {/if}
                <input type="file" name="{$actionid}picture_upload" size="50" maxlength="255" />
            </p>
        </div>
        

        <div class="pageoverflow">
            <p class="pagetext">{$mod->ModLang('color_description')}:</p>
            <p class="pageinput">{$input_color_description}</p>
        </div>
*}
        <div class="pageoverflow">
            <p class="pagetext">{$mod->ModLang('active')}:</p>
            <p class="pageinput">{$input_active}</p>
        </div>		

        <div class="pageoverflow">
            <p class="pagetext">&nbsp;</p>
            <p class="pageinput">
                <input name="{$actionid}submit" id="lise_submit" value="{lang('submit')}" type="submit" />
                <input name="{$actionid}cancel" id="lise_cancel" value="{lang('cancel')}" type="submit" />
                <input name="{$actionid}apply" id="lise_apply" value="{lang('apply')}" type="submit" />
                <input name="{$actionid}save_create" id="lise_save_create" value="{$mod->ModLang('save_create')}" type="submit" />				
            </p>
        </div>
        {$endform}
    </div>
</div>
