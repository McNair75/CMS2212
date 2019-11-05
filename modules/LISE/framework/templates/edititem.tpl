{if $activelang}{$langList}<br/>{/if}
{$backlink}
<h3>{$header}</h3>
{$startform}
<div class="pageoverflow">
    <p class="pageinput">
        <input name="{$actionid}submit" id="lise_submit" value="{lang('submit')}" type="submit" />
        <input name="{$actionid}cancel" id="lise_cancel" value="{lang('cancel')}" type="submit" />
        <input name="{$actionid}apply" id="lise_apply" value="{lang('apply')}" type="submit" />
        <input name="{$actionid}save_create" id="lise_save_create" value="{$mod->ModLang('save_create')}" type="submit" />
    </p>
</div>
<!-- start tab -->
<div id="page_tabs">
    <div id="edititem">
        {$title}
    </div>
    {if is_array($itemObject) && count($itemObject)}
        {foreach from=$itemObject->fielddefs item='fielddef'}
            {if $fielddef->type === 'Tabs'}
                {$fielddef->displayTabHeader()}
            {/if}
        {/foreach}
    {/if}
</div>
<!-- end tab //-->
<!-- start content -->
<div id="page_content"> 
    <div id="edititem_result"></div>
    <div id="edititem_c">

        {if isset($input_active)}
            <div class="pageoverflow">
                <p class="pagetext">{$mod->ModLang('active')}:</p>
                <p class="pageinput">{$input_active}</p>
            </div>
        {/if}

        {if $mod->GetPreference('display_create_date', 0) == 1}
            <div class="pageoverflow">
                <p class='pagetext'>{$mod->ModLang('create_time', '')}: <em style='font-weight: normal;'>{$itemObject->create_time}</em></p>
            </div>
        {/if}

        <div class="pageoverflow">
            <p class="pagetext">{if ($mod->prefix == "liseseo")}SKU{else}{$mod->GetPreference('item_title', '')}{/if}*: {if $maps_turn}<span id="add_demo" style="font-size:90%; font-weight:bold;"></span>{/if}</p>
            <p class="pageinput">{$input_title} {if $maps_turn}{$valid_maps}{/if}</p>
        </div>
        {if $maps_turn}
            <div class="pageoverflow">
                <div id="infowindow-content">
                    <span id="place-name" class="title"></span>
                    <br/><span id="place-address"></span>
                </div>
                <div id="maps" style="height: 400px; border: 1px solid #ccc;"></div>
            </div>
        {/if}
        {if !($mod->prefix == "liseseo")}
            <div class="pageoverflow">
                <p class="pagetext">{$mod->ModLang('desc', '')}:</p>
                <p class="pageinput">{$input_desc}</p>
            </div>


            <div class="pageoverflow">
                <p class="pagetext">{$mod->ModLang('alias')}:</p>{$alias|default:''}
                <p class="pageinput">{$input_alias}</p>
            </div>

            {if $mod->GetPreference('display_create_date', 0) == 1}
                <div class="pageoverflow">
                    <p class="pagetext">{$mod->ModLang('url')}:</p>{$url|default:''}
                    <p class="pageinput">{$input_url}</p>
                </div>
            {/if}



        {/if}

        {if $time_control_turn == 1}
            <div class="pageoverflow">
                <p class="pagetext">{$mod->ModLang('time_control')}:</p>
                <p class="pageinput">{$input_time_control}</p>
            </div>
        {/if}


        <div id="expiryinfo"{if $use_time_control != true} style="display:none;"{/if}>
            <div class="pageoverflow">
                <p class="pagetext">{$mod->ModLang('start_time')}:</p>
                <p class="pageinput">{$input_start_time}</p>
            </div>

            <div class="pageoverflow">
                <p class="pagetext">{$mod->ModLang('end_time')}:</p>
                <p class="pageinput">{$input_end_time}</p>
            </div>
        </div>

        {if is_array($itemObject) && count($itemObject)}
            {foreach from=$itemObject->fielddefs item='fielddef'}
                {$fielddef->RenderInput($actionid, $returnid)}
            {/foreach}
        {/if} 

        {*
        <!--added by peterson for separation of jm file picker field-->
        {if count($itemObject)}
        {foreach from=$itemObject->fielddefs item='fielddef'}
        {if $fielddef->type != 'JMFilePicker'}
        {$fielddef->RenderInput($actionid, $returnid)}
        {else}
        {$fielddef->RenderJMFInput($actionid, $returnid, $itemObject)}
        {break}
        {/if}
        {/foreach}
        {/if}
         
        *}

        {if $extra1_enabled_turn == 1}
            <div class="pageoverflow">
                <p class="pagetext">{$mod->ModLang('extra1_enabled')}:</p>
                <p class="pageinput">{$input_extra1_enabled}</p>
                <p class="pageinput">{$input_extra1_enabled_wysiwyg}</p>
            </div>
        {/if}

        {if $extra2_enabled_turn == 1}
            <div class="pageoverflow">
                <p class="pagetext">{$mod->ModLang('extra2_enabled')}:</p>
                <p class="pageinput">{$input_extra2_enabled}</p>
                <p class="pageinput">{$input_extra2_enabled_wysiwyg}</p>
            </div>
        {/if}

        {if $extra3_enabled_turn == 1}
            <div class="pageoverflow">
                <p class="pagetext">{$mod->ModLang('extra3_enabled')}:</p>
                <p class="pageinput">{$input_extra3_enabled}</p>
                <p class="pageinput">{$input_extra3_enabled_wysiwyg}</p>
            </div>
        {/if}
        {if $itemObject->owner_name}
            <div class="pageoverflow">
                <p class="pagetext">{admin_icon icon="groupassign.gif" alt="Log"} <font style="font-weight:normal; font-size:11px;">Created: {$itemObject->owner_name} | {$itemObject->create_time} / Edited: {$itemObject->owner_name} | {$itemObject->modified_time}</font></p>
            </div>
        {/if}

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
</div>
{$endform}
<!-- end content //-->
<script type="text/javascript">
    var action_id = '{$actionid}';
    var item_id = '{$itemObject->item_id|default:-1}';
    var ajax_url1 = '{$ajax_get_url}';
    var ajax_url2 = '{$ajax_get_alias}';
    var ajax_url3 = '{$ajax_getgeocode}';
    var manually_changed1 = item_id;
    var manually_changed2 = item_id;
    var finished_setup = 0;
    var ajax_xhr1 = 0;
    var ajax_xhr2 = 0;
    var ajax_timeout1;
    var ajax_timeout2;
    ajax_url1 = ajax_url1.replace(/amp;/g, '') + '&suppressoutput=1';
    ajax_url2 = ajax_url2.replace(/amp;/g, '') + '&suppressoutput=1';
    ajax_url3 = ajax_url3.replace(/amp;/g, '') + '&suppressoutput=1';

    var maps_turn = '{$maps_turn}';
    var search_text = '{$search_text}';
    var lat = '{$lat}';
    var lng = '{$lng}';

    var lat_value = jQuery('input[name="{$actionid}{$lat}"]').val();
    var lng_value = jQuery('input[name="{$actionid}{$lng}"]').val();

    function ajax_geturl() {
        var form = $('#lise_edititem form');
        var vtitle = $('#{$actionid}title').val();
        ajax_xhr = $.post(ajax_url1, {
            'title': vtitle,
            'itemid': item_id
        }, function (retdata) {
            $('#{$actionid}url').val(retdata);
            ajax_xhr1 = 0;
        });
    }

    function ajax_get_alias() {
        var form = $('#lise_edititem form');
        var vtitle = $('#{$actionid}title').val();
        ajax_xhr = $.post(ajax_url2, {
            'title': vtitle
        }, function (retdata) {
            $('#{$actionid}alias').val(retdata);
            ajax_xhr2 = 0;
        });
    }

    function on_change() {
        if (manually_changed1 < 1 && finished_setup == 1) {
            // ajax function to get a unique url given a title.
            if (ajax_timeout1 != undefined)
                clearTimeout(ajax_timeout1);
            if (ajax_xhr1 = 0)
                xhr.abort();
            ajax_timeout1 = setTimeout(ajax_geturl, 500);
        }
        if (manually_changed2 < 1 && finished_setup == 1) {
            // ajax function to get a unique alias given a title.
            if (ajax_timeout2 != undefined)
                clearTimeout(ajax_timeout2);
            if (ajax_xhr2 = 0)
                xhr.abort();
            ajax_timeout1 = setTimeout(ajax_get_alias, 500);
        }
    }

    jQuery(document).ready(function () {

        if (maps_turn) {

            jQuery('.valid_maps').click(function () {
                var address = jQuery('input[name="{$actionid}{$search_text}"]').val();
                ajax_xhr = $.post(ajax_url3, { address: address }, function (retdata) {
                    reloadMap(JSON.parse(retdata).lat, JSON.parse(retdata).lon);

                });
            });
        }


        $('{$actionid}url').keyup(function () {
            var val = $(this).val();
            manually_changed1 = 0
            if (val != '')
                manually_changed1 = 1;
        });

        $('{$actionid}alias').keyup(function () {
            var val = $(this).val();
            manually_changed1 = 0
            if (val != '')
                manually_changed2 = 1;
        });

        $('form').ajaxStart(function () {
            $('*').css('cursor', 'progress');
        });

        $('form').ajaxStop(function () {
            $('*').css('cursor', 'auto');
        });

        $('#{$actionid}title').keyup(function () {
            on_change();
        });

        finished_setup = 1;


        jQuery('[name=m1_apply]').live('click', function () {
            if (typeof tinyMCE != 'undefined') {
                tinyMCE.triggerSave();
            }

            var data = jQuery('form').find('input:not([type=submit]), select, textarea').serializeArray();

            data.push({
                'name': 'm1_ajax',
                'value': 1
            });
            data.push({
                'name': 'm1_apply',
                'value': 1
            });
            data.push({
                'name': 'showtemplate',
                'value': 'false'
            });

            var url = jQuery('form').attr('action');

            jQuery.post(url, data, function (resultdata, text) {
                var resp = jQuery(resultdata).find('Response').text();
                var details = jQuery(resultdata).find('Details').text();
                var htmlShow = '';
                if (resp === 'Success' && details !== '') {
                    htmlShow = '<div class="pagemcontainer"><p class="pagemessage">' + details + '<\/p><\/div>';
                } else {
                    htmlShow = '<div class="pageerrorcontainer"><ul class="pageerror">';
                    htmlShow += details;
                    htmlShow += '<\/ul><\/div>';
                }
                jQuery('#edititem_result').html(htmlShow);
                window.setTimeout(function () {
                    $('.pagemcontainer').hide();
                }, 9000)
            }, 'xml');
            return false;
        });
    });
</script>
{if $maps_turn}
    <script>
        var map;
        var geocoder;
        var marker;
        var markers = [];
        var infowindow;

        var init_lat = '10.823099';
        var init_lng = '106.629664';

        function initMap() {
            
            if(lat_value && lat_value){
                init_lat = lat_value;
                init_lng = lng_value;
            }
            var latlng = new google.maps.LatLng(init_lat,init_lng);
            var myOptions = {
                zoom: 17,
                center: latlng
            };
            map = new google.maps.Map(document.getElementById("maps"),
                    myOptions);

            geocoder = new google.maps.Geocoder();
            var dragged = false;
            //if(lat_value && lat_value){
                var im = 'http://i.stack.imgur.com/orZ4x.png';
                
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: im,
                    draggable:true,
                });

                google.maps.event.addListener(marker, 'dragend', function(){
                    // Drag Maker
                    dragged = true;
                    setTimeout(function(){ dragged = false; }, 200);
                    geocodePosition(marker.getPosition());
                });

                google.maps.event.addListener(map, 'dragstart', function() {
                    // Start Drag Maker
                });

                google.maps.event.addListener(map, 'center_changed', function() {
                    dragged = true;
                    setTimeout(function(){ dragged = false; }, 200);
                    marker.setPosition(map.getCenter());
                });

                google.maps.event.addListener(map, 'dragend', function(){
                    dragged = true;
                    setTimeout(function(){ dragged = false; }, 200);
                    geocodePosition(marker.getPosition());
                });
            //}

            clickMap(geocoder);
            if (dragged) return;
        }

        function geocodePosition(pos) 
        {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({ latLng: pos }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    jQuery('#add_demo').html(results[0].formatted_address);
                    jQuery('input[name="{$actionid}{$formatted_address}"]').val(results[0].formatted_address);
                    jQuery('input[name="{$actionid}{$lat}"]').val(pos.lat().toFixed(6));
                    jQuery('input[name="{$actionid}{$lng}"]').val(pos.lng().toFixed(6));	
                } else {
                    jQuery('#add_demo').html('Cannot determine address at this location.'+ status).show(100);
                }
            });
        }

        function reloadMap(lat, lng) {
            var latlng = new google.maps.LatLng(lat, lng);
            var myOptions = {
                zoom: 18,
                center: latlng
            };
            map = new google.maps.Map(document.getElementById("maps"),
                    myOptions);
            geocoder = new google.maps.Geocoder();

            var im = 'http://i.stack.imgur.com/orZ4x.png';

            var dragged = false;
            marker = new google.maps.Marker({
                position: latlng,
                map: map,
                icon: im,
                draggable:true,
            });
            
            google.maps.event.addListener(marker, 'dragend', function(){
                // Drag Maker
                dragged = true;
                setTimeout(function(){ dragged = false; }, 200);
                geocodePosition(marker.getPosition());
            });

            google.maps.event.addListener(map, 'dragstart', function() {
                // Start Drag Maker
            });

            google.maps.event.addListener(map, 'center_changed', function() {
                dragged = true;
                setTimeout(function(){ dragged = false; }, 200);
                marker.setPosition(map.getCenter());
            });

            google.maps.event.addListener(map, 'dragend', function(){
                dragged = true;
                setTimeout(function(){ dragged = false; }, 200);
                geocodePosition(marker.getPosition());
            });

       		getResultFillData(geocoder,latlng);
            clickMap(geocoder);
            if (dragged) return;
        }
	
	function getResultFillData(geocoder,latlng){
		geocoder.geocode({
                    'latLng': latlng
                }, function (results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            jQuery('input[name="{$actionid}{$formatted_address}"]').val(results[0].formatted_address);
                        }
                    }
                });
	        jQuery('input[name="{$actionid}{$lat}"]').val(latlng.lat().toFixed(6));
	        jQuery('input[name="{$actionid}{$lng}"]').val(latlng.lng().toFixed(6));		
	}

        function clickMap(geocoder, infowindow) {
	        var infowindow = new google.maps.InfoWindow();
            var infowindowContent = document.getElementById('infowindow-content');
            infowindow.setContent(infowindowContent);

            var marker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29)
            });

            google.maps.event.addListener(map, 'click', function (event) {

                geocoder.geocode({
                    'latLng': event.latLng
                }, function (results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            jQuery('input[name="{$actionid}{$formatted_address}"]').val(results[0].formatted_address);
                        }
                    }
                });

                marker.setPosition(event.latLng);

                var yeri = event.latLng;

                var latlongi = "(" + yeri.lat().toFixed(6) + " , " +
                        +yeri.lng().toFixed(6) + ")";

                jQuery('input[name="{$actionid}{$lat}"]').val(yeri.lat().toFixed(6));
                jQuery('input[name="{$actionid}{$lng}"]').val(yeri.lng().toFixed(6));		
		
                infowindow.setContent(latlongi);
                infowindow.open(map, marker);
            });
        }


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={$maps_key}&libraries=places&callback=initMap" async defer></script>
{/if}