/*********************************************************************************
 * Simple Chat is a chat for SugarCRM developed by Letrium, Ltd.
 * Copyright (C) 2006 - 2013 Letrium Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY;  without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See  the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if  not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51  Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * You can contact Letrium Ltd. at email address crm@letrium.com.
 *
 * SimpleChat version 3.2, Copyright (C) Letrium Ltd., Taras Machyshyn.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Letrium" label.
 *
 *For more information on how to apply and follow the GNU GPL, see http://www.gnu.org/licenses.
 ********************************************************************************/
SUGAR.util.doWhen("typeof $ != 'undefined'", function () {
    if (loaded == true) return;

    function wConsole(o, prop) {
        var p = {};
        $.extend(p, prop);
        var txt = "<b>" + o.find(".n:first").html() + "::  </b>";
        for (var property in p) {
            var pr = p[property];
            txt += "<br>" + property + " = " + pr
        }
        txt += "<br><hr>";
        $("#mb_console").append(txt)
    }
    $(".containerPlus").buildContainers({
        containment: "document",
        minimizeEffect: "fade",
        effectDuration: 0,
        elementsPath: "modules/let_Chat/elements/",
        onResize: function (o) {
            wConsole(o, {
                resized: true,
                width: o.outerWidth(),
                height: o.outerHeight()
            })
        },
        onClose: function (o) {
            wConsole(o, {
                closed: o.mb_getState("closed")
            })
        },
        onRestore: function (o) {
            tmpLeft = $('#myChat').css('left');
            if (tmpLeft == 'auto') tmpLeft = '0';
            else tmpLeft = tmpLeft.replace("px", "");
            tmp = parseInt(tmpLeft) + 355;
            // letrium v
            //if (($('#myChat').css('top') == '0px') || ($('#myChat').css('top') == 'auto')) {
            //    tmpTop = myCurrentTop
            //} else {
            	tmpTop = $('#myChat').css('top'); 
            //}
            if (tmpTop == 'auto') tmpTop = '0px';
            // letrium v END
            widthWindow = $(window).width();
            if (widthWindow < (tmp + 200)) {
                tmp = parseInt(tmpLeft) - 195
            }
            $('#myUsers').animate({
                'top': tmpTop,
                'left': tmp + 'px'
            }, 100);
            if ($.browser.msie) {
                $('#myUsers').css('width', '200px')
            }
        },
        onCollapse: function (o) {
            wConsole(o, {
                collapse: o.mb_getState("collapsed")
            });
            $('#beforeChat').css({
                'left': '0',
                'bottom': '50px',
                'top': 'auto'
            });
            myCurrentTop = $('#myChat').css('top');
            $('#myChat').css({
                'top': ''
            });
            // letrium v
            if ($('ul.tabs.tabs1 li').length > 1 && $('.tab-current a').attr('usr_id') != "all") setLastRead();
            // letrium v END
            if ($('.blinking').length > 0) start_blink()
        },
        onIconize: function (o) {
            wConsole(o, {
                iconized: o.mb_getState("iconized")
            })
        },
        onDrag: function (o) {
            if (($('#myUsers').attr('iconized') == 'false') && (o.attr('id') != 'myUsers')) $('#myUsers').containerIconize();
            wConsole(o, {
                top: o.offset().top,
                left: o.offset().left
            })
        }
    })
});
var menu2 = null;
var myCurrentTop = '0px';
var blink_time;
var blinkingClass = getBlinkingClass(chatSkin);

// letrium v
var move_trigger = true;
// letriumv END
SUGAR.util.doWhen("typeof $ != 'undefined'", function () {
    if (loaded == true) return;
    loaded = true;
    myChatHeight = 430;
    myChatHeightMin = 50;
    $('#myChat .n').css('padding-left', '5px');
    $('#myUsers .icon').attr('width', '24').attr('height', '24').css('margin-top', '-5px');
    $('.collapsedContainer').click(function () {
        not_collapse()
    });
    $('#myChat font').html(meo);
    // letrium v
    switch(expand_collapse_chat){
    	case 'Double click':
    		$('.n').dblclick(function () {
				not_collapse()
			});
    		break;
    		
        case 'One click':
        	$('.n').click(function () {
        		if (move_trigger){
					not_collapse();
				}
			});
        	break;
    }
    $('.n').mousemove(function () {
		move_trigger = false;
	});
    $('.n').mousedown(function () {
		move_trigger = true;
	});
    // letrium v END
    
    $('.history').popupWindow({
        height: 500,
        width: 625,
        top: 50,
        left: 50,
        scrollbars: 1
    });
    if ($.browser.msie) {
        $('#myChat').css('width', '360px')
    }
});
myOwnLoad();

function clearUser() {
    $('#daddy-shoutbox-touser').html('');
    $('#for_user').attr('value', 'all');
    $(".shoutbox-list-selectuser").attr('class', 'shoutbox-list-user');
    $('.clearuser').hide();
    $.cookie('to_user', 'all');
    $('.daddy-shoutbox-list').hide();
    $('#daddy-shoutbox-list').show();
    $('#daddy-shoutbox-list').scrollTop($('#daddy-shoutbox-list')[0].scrollHeight);
    $('ul.tabs.tabs1 li').removeClass('tab-current');
    $('#user_tab_all').addClass('tab-current');
    $('ul.tabs.tabs1').css('margin-left', '-40px');
    $('#mess').focus()
}

function myOwnLoad() {
    if ($('#daddy-shoutbox-list')[0].scrollHeight == 0) setTimeout(myOwnLoad, 100);
    else {
        $('#daddy-shoutbox-list').scrollTop($('#daddy-shoutbox-list')[0].scrollHeight);
        myAlign('myChat')
    }
}

function myAlign(myName) {
    return '';
    PosWind = parseInt($(window).height());
    Pos = parseInt($('#' + myName).css('top')) + parseInt($('#' + myName).height());
    if (Pos >= PosWind) {
        menu2 = $(window).height() - 10;
        offset2 = menu2 - $('#' + myName).height() + $(document).scrollTop() + "px";
        $('#' + myName).animate({
            top: offset2
        }, {
            duration: 1,
            queue: false
        })
    }
}

function not_collapse() {
    refresh("without");
    temp = $('#myChat').attr('collapsed');
    if ((temp == 'false')) {
        myCurrentTop = $('#myChat').css('top');
        $('#beforeChat').css({
            'left': '0',
            'bottom': '440px'
        });
        $('#myChat').css({
            'top': ''
        });
        setTimeout(function () {
            $('#myText').css({
                'overflow': 'hidden'
            })
        }, 100);
        if ($.browser.msie) {
            $('#myChat .o').css('zoom', '')
        }
        // letrium v
        var thisUser = $('ul.tabs.tabs1 li.t1.tab-current a:first').attr('usr_id');
        // letrium v END
        if (thisUser != "all") select_tab(thisUser);
        else {
            checkArrow();
            $('#daddy-shoutbox-list').scrollTop($('#daddy-shoutbox-list')[0].scrollHeight);
            $('#mess').focus()
        }
        stop_blink()
    }
    if (($('#myUsers').attr('iconized') == 'false')) {
        $('#myUsers').containerIconize()
    }
}

function blink() {
    if ($('#myChat').hasClass(blinkingClass)) {
        $('#myChat').removeClass(blinkingClass).addClass(chatSkin)
    } else {
        $('#myChat').removeClass(chatSkin).addClass(blinkingClass)
    }
}

function start_blink() {
    blink();
    blink_time = setTimeout(start_blink, 500)
}

function stop_blink() {
    clearTimeout(blink_time);
    $('#myChat').removeClass(blinkingClass).addClass(chatSkin)
}

function getBlinkingClass(skinName) {
    var bClass = 'blink';
    if (skinName != 'default') {
        bClass = bClass + skinName.substr(0, 1)
    }
    return bClass
}

function getSelectedUser() {
	// letrium v
    var selectedUser = $('ul.tabs.tabs1 li.t1.tab-current a:first').attr('usr_id');
    // letrium v END
    if (selectedUser == 'all') {
        selectedUser = ''
    }
    return selectedUser
}
