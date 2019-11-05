{$type = $fielddef->GetOptionValue('type')}
{$opts = $fielddef->GetColorList()}
{$dfc = $fielddef->ColorSettingsDefault()}
<style type="text/css">
    .bcPicker-picker{
        width: 100%;
        height: 100%;
        position:  relative;
        cursor: pointer;
    }

    .bcPicker-palette{
        width: 120px;
        position: absolute;
        top: 16px;
        padding: 5px;
        border: 1px solid #efefef;
        background-color: #fdfdfd;
        z-index: 10;
        display: none;
    }

    .bcPicker-palette > .bcPicker-color{
        width: 14px;
        height: 14px;
        margin: 2px;
        display: inline-block;
        border: 1px solid #efefef;
    }
    .color-picker{
        width: 30px;
        height: 30px;
        padding: 5px;
        border:1px solid #c0c0c0;
    }
    .display-inline{
        display: inline-block;
    }
    .middle-hex{
        min-width: 100px;
        font-size: 13px;
        color: #bdbdbd;
        vertical-align: top;
        margin-top: 13px;
        margin-left: 10px;
        text-transform: uppercase;
    }
    .middle-hex span{
        color: #292929;
        margin-left: 10px;
    }
    .bcPicker-palette{
        top: 36px !important;
    }
</style>
<div class="pageoverflow">
    <p class="pagetext">{$fielddef->GetName()}{if $fielddef->IsRequired()}*{/if}:</p>
    <p class="pageinput">
        {if $fielddef->GetDesc()}({$fielddef->GetDesc()})<br />{/if}
        <input type="hidden" name="{$actionid}customfield[{$fielddef->GetId()}]" value="" />
    <div id="middle">
        <div class="color-container">
            <div id="bcPicker" class="color-picker display-inline"></div>
            <div class="middle-hex  display-inline">HEX <span>#000000</span></div>
            <div class="middle-hex  display-inline"><span>RGB(0, 0, 0)</span></div>
        </div>
    </div>
</p>
</div>
{literal}
    <script>
        $(document).ready(function () {
            // Toggle color picker
            $('body').on('click', '.bcPicker-picker', function () {
                $.fn.bcPicker.toggleColorPalette($(this));
            });
            // Pick a color
            $('.bcPicker-palette').on('click', '.bcPicker-color', function () {
                $.fn.bcPicker.pickColor($(this));
            });
        });

        (function ($) {
            /**
             * Private variables
             **/
            var templates = {
                picker: $('<div class="bcPicker-picker"></div>'),
                palette: $('<div class="bcPicker-palette"></div>'),
                color: $('<div class="bcPicker-color"></div>')
            };

            /**
             * Color picker assembly
             **/
            $.fn.bcPicker = function (options) {
                return this.each(function () {
                    var elem = $(this),
                            colorSet = $.extend({}, $.fn.bcPicker.defaults, options),
                            defaultColor = $.fn.bcPicker.toHex((elem.val().length > 0) ? elem.val() : colorSet.defaultColor),
                            picker = templates.picker.clone(),
                            palette = templates.palette.clone(),
                            color;

                    // add position relative to root element
                    elem.css('position', 'relative');

                    // append picker
                    elem.append(picker);
                    picker.css('background-color', defaultColor);

                    // append palette
                    elem.append(palette);

                    // assembly color palette
                    $.each(colorSet.colors, function (i) {
                        color = templates.color.clone();
                        //color.css('background-color', $.fn.bcPicker.toHex(colorSet.colors[i]));
                        color.attr('data-color', colorSet.colors[i]);
                        color.attr('data-color-id', i);
                        if (colorSet.colors[i].length > 6) {
                            color.attr('style', 'background: transparent url("../uploads/' + colorSet.colors[i] + '")');
                        } else {
                            color.css('background-color', $.fn.bcPicker.toHex(colorSet.colors[i]));
                        }

                        palette.append(color);
                    });
                });
            };

            /**
             * Color picker functions
             **/
            $.extend(true, $.fn.bcPicker, {

                /**
                 * Toggle color palette
                 **/
                toggleColorPalette: function (elem) {
                    elem.next().toggle('fast');
                },

                /**
                 * Pick color action
                 **/
                pickColor: function (elem) {
                    var hidden_name = '{/literal}{$actionid}customfield[{$fielddef->GetId()}]{literal}';
                                    // get selected color
                                    // var pickedColor = elem.css('background-color');
                                    var datacolor = elem.attr('data-color');
                                    var datacolorid = elem.attr('data-color-id');
                                    // set picker with selected color
                                    if (datacolor.length > 6) {
                                        elem.parent().parent().find('.bcPicker-picker').attr('style', 'background: transparent url("../uploads/' + datacolor + '")');
                                    } else {
                                        elem.parent().parent().find('.bcPicker-picker').css('background-color', $.fn.bcPicker.toHex(datacolor));
                                    }
                                    elem.parent().toggle('fast');
                                    $('input[name="' + hidden_name + '"]').val(datacolorid);
                                },

                                /**
                                 * Convert color to HEX value
                                 **/
                                toHex: function (color) {
                                    // check if color is standard hex value
                                    if (color.match(/[0-9A-F]{6}|[0-9A-F]{3}$/i)) {
                                        return (color.charAt(0) === "#") ? color : ("#" + color);
                                        // check if color is RGB value -> convert to hex
                                    } else if (color.match(/^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/)) {
                                        var c = ([parseInt(RegExp.$1, 10), parseInt(RegExp.$2, 10), parseInt(RegExp.$3, 10)]),
                                                pad = function (str) {
                                                    if (str.length < 2) {
                                                        for (var i = 0, len = 2 - str.length; i < len; i++) {
                                                            str = '0' + str;
                                                        }
                                                    }
                                                    return str;
                                                };
                                        if (c.length === 3) {
                                            var r = pad(c[0].toString(16)),
                                                    g = pad(c[1].toString(16)),
                                                    b = pad(c[2].toString(16));
                                            return '#' + r + g + b;
                                        }
                                        // else do nothing
                                    } else {
                                        return false;
                                    }
                                }

                            });

                            /**
                             * Default color values
                             **/
                            $.fn.bcPicker.defaults = {
                                // default color
                                defaultColor: "000000",

                                // default color set
                                colors: [

                                ],

                                // extend default set
                                addColors: [],
                            };
                        })(jQuery);

    </script>
    <script type="text/javascript">
        var _setting = '{/literal}{$dfc}{literal}';
        $('#bcPicker').bcPicker(JSON.parse(_setting));
        $('.bcPicker-palette').on('click', '.bcPicker-color', function () {
            var color = $(this).css('background-color');
            $(this).parent().parent().next().children().text($.fn.bcPicker.toHex(color));
            $(this).parent().parent().next().next().children().text(color);
        });
    </script>
{/literal}