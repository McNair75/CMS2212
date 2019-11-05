{$startform}
<fieldset>
    <legend>Maps Options</legend>
    
    <div class="pagewarning">
        <h3>{$mod->ModLang('notice')}</h3>
        <p>{$mod->ModLang('options_notice')}</p>
    </div>	

    <div class="pageoverflow">
        <p class="pagetext">Maps Key:</p>
        <p class="pageinput">{$maps_key}</p>
    </div>
        
    <div class="pageoverflow">
        <p class="pagetext">Search Text:</p>
        <p class="pageinput">{$input_search_text}</p>
    </div>

    <div class="pageoverflow">
        <p class="pagetext">Latitude:</p>
        <p class="pageinput">{$input_latitude}</p>
    </div>
    <div class="pageoverflow">
        <p class="pagetext">Longitude:</p>
        <p class="pageinput">{$input_longitude}</p>
    </div>

    <div class="pageoverflow">
        <p class="pagetext">Formatted Address:</p>
        <p class="pageinput">{$input_formatted_address}</p>
    </div>
</fieldset> 

<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">
        {$submit}
    </p>
</div>

{$endform}
