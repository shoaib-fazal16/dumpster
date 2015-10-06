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
var count = 0;
var count_user = 0;
var arr_last_read = new Array;
var arr_last_read_users = new Array;
var arr_last_read_times = new Array;
var visible_tabs = new Array;
var first_user = "all";
var files = "modules/let_Chat/";
var lastTime = 0;
var del_history = true;
var display_num = 50;
var lastJsonLen = 0;
var myCurrentUser = "";
var topLeft = 0;
var initLoad = true;
var Loaded = false;

function Add_0(val) {
    return val < 10 ? "0" + val : val
}

function prepare(response) {
    var d = new Date;
    d.setTime(response.time * 1E3);
    var mytime = Add_0(d.getHours()) + ":" + Add_0(d.getMinutes()) + ":" + Add_0(d.getSeconds());
    var user = "to " + response.foruser + ":";
    if (response.nickname != undefined && response.message != undefined) {
        lastJsonLen = 1;
        count++;
        // letrium v
        var user_name_sys = response.nickname;
        // letrium v END
        if (response.foruser == "all") {
            if (response.nickname == myCurrentUser) toFrom = "<span usr_id=\""+myCurrentUser+"\">" + response.display_from_name + ":</span>";
            else toFrom = '<span class="users-list" usr_id="'+myCurrentUser+'" onclick="getUser(\'user-list-' + response.nickname + "')\">" + response.display_from_name + ":</span>";
            user = response.display_from_name + ":";
            user_name_sys = 'all';
        } else if (response.nickname == myCurrentUser) {
        	toFrom = '<span class="users-list isay" usr_id="'+myCurrentUser+'" onclick="getUser(\'user-list-' + response.foruser + "')\">to " + response.display_to_name + ":</span>";
        }
        else if (response.foruser == myCurrentUser) {
            toFrom = '<span class="users-list tome" usr_id="'+myCurrentUser+'" onclick="getUser(\'user-list-' + response.nickname + "')\">" + response.display_from_name + ":</span>";
            user = response.display_from_name + ":";
        }
        var string = '<div class="shoutbox-list" id="list-' + count + '">' + '<span class="shoutbox-list-time">' + mytime + "</span>" + '<span class="shoutbox-list-nick">' +
            toFrom + "</span>";
        if (response.foruser != "all") string = string + '<span class="shoutbox-list-privat-message">' + response.message + "</span>";
        else string = string + '<span class="shoutbox-list-message">' + response.message + "</span>"; + "</div>"
    }
    var row1 = mytime + " " + user + " " + response.message;
    var dupl = false;
    var row2;
    $("span.shoutbox-list-time:contains(" + mytime + ")").each(function () {
        var user = $(this).parent().find(".shoutbox-list-nick span").text();
        row2 = mytime + " " + user + " " + $(this).parent().find("span:last").text();
        if (row1 ==
            row2) dupl = true
    });
    if (dupl) return "";
    return string
}

function getUser_mix(userId) {
    if (userId != "" && userId != null) {
        var userId_sys = replaceJquerySpecialChars(userId);
        userId_sys = userId_sys.replace('shoutbox-list-', '');
        var currerntUser_sys = $("#" + userId_sys).find("span").attr('usr_id');
        var currerntUser = $("#" + userId_sys).find("span").text();
        var tmp = "";
        $(".shoutbox-list-selectuser").attr("class", "shoutbox-list-user");
        $("#" + userId_sys).attr("class", "shoutbox-list-selectuser");
        if (currerntUser_sys == null) {
            tmp = '<span style="color: red;">(' + userId.substring(10) + " is offline)</span>";
            currerntUser_sys = ""
        }
        $("#for_user").attr("value", currerntUser_sys);
        if (currerntUser_sys == "") $.cookie("to_user", "all");
        else $.cookie("to_user", currerntUser_sys);
        $("#daddy-shoutbox-touser").empty();
        if (tmp != "") currerntUser_sys = tmp;
        $("#daddy-shoutbox-touser").append("to " + currerntUser + "&nbsp;");
        $(".clearuser").show()
    } else {
        clearUser();
        first_user = "all"
    }
    return false
}

function getUser(userId) {
    if (userId != "" && userId != null) {
        userId_sys = replaceJquerySpecialChars(userId);
        userId_sys = userId_sys.replace('shoutbox-list-', '');
        var currerntUser_sys = $("#" + userId_sys).find("span").attr('usr_id');
        var currerntUser = $("#" + userId_sys).find("span").text();
        var tmp = "";
        $(".shoutbox-list-selectuser").attr("class", "shoutbox-list-user");
        $("#" + userId_sys).attr("class", "shoutbox-list-selectuser");
        if (currerntUser == null || currerntUser == '') {
            tmp = '<span style="color: red;">(' + userId.substring(10) + " is offline)</span>";
        }
        $("#for_user").attr("value", currerntUser_sys);
        if (currerntUser == "") $.cookie("to_user", "all");
        else $.cookie("to_user", currerntUser);
        $("#daddy-shoutbox-touser").empty();
        if (currerntUser != "") {
            if ($("#user_tab_" + currerntUser_sys).length == 0) {
                $("ul.tabs.tabs1").append('<li class="t1" id="user_tab_' + currerntUser_sys + '" ><a usr_id="'+currerntUser_sys+'">' + currerntUser + '<img src="modules/let_Chat/images/close.png" style="width:10px; top:0px;" onClick = "closeTab(this);" /></a></li>');
                $("#user_tab_" + currerntUser_sys).click(function () {
                    thisUser = $("#" + this.id + " a:first").attr('usr_id');
                    setLastRead();
                    if (!$("#" + this.id).hasClass("closed")) {
                        getUser_mix("user-list-" + thisUser);
                        
                        select_tab(thisUser)
                    }
                });
                $("#daddy-shoutbox-list").after('<div class="daddy-shoutbox-list" id="daddy-shoutbox-list_user_' + currerntUser_sys + '" style="overflow: auto; height: 230px; display:none;"></div>')
            }
            if (tmp != "") currerntUser = tmp;
            //currerntUser_sys = replaceJquerySpecialChars(currerntUser_sys);
            $("#daddy-shoutbox-touser").append("to " + currerntUser + "&nbsp;");
            $(".clearuser").show();
            $("#user_tab_" + currerntUser_sys).removeClass("closed");
            select_tab(currerntUser_sys)
        } else {
            clearUser();
            first_user = "all"
        }
    }
    return false
}

function setLastRead() {
    var time = "";
    var all_time = "all#00:00:00";
    var len = arr_last_read_users.length;
    for (i = 0; i < len; i++) arr_last_read_users.pop();
    arr_last_read_users.push("all");
    len = arr_last_read_times.length;
    for (i = 0; i < len; i++) arr_last_read_times.pop();
    arr_last_read_times.push("00:00:00");
    var now = new Date;
    var tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
    $(".daddy-shoutbox-list").each(function () {
        var curr_id = this.id;
        curr_id = replaceJquerySpecialChars(curr_id);
        if (this.id != "daddy-shoutbox-list") {
            user =
                this.id.slice(25);
            arr_last_read_users.push(user);
            if (time != "") time += ";";
            if ($("#" + curr_id + " .shoutbox-list-time:last").text() != "")
                if (!$("#user_tab_" + user + " a").hasClass("blinking")) time += user + "#" + $("#" + curr_id + " .shoutbox-list-time:last").text();
                else time += user + "#00:00:00";
                else time += user + "#00:00:00";
            arr_last_read_times.push(time.slice(-8))
        }
    });
    if (time != "") all_time += ";" + time;
    $.cookie("last_read", all_time, {
        expires: tomorrow
    });
    arr_last_read = all_time.split(";")
}

function count_visible_tabs() {
    var new_array = new Array;
    var start = first_user;
    start_sys = replaceJquerySpecialChars(start);
    sum = $("#user_tab_" + start_sys).css("width").slice(0, -2) - 0 + 7;
    while (sum < 320) {
        if ($("#user_tab_" + start_sys).css("display") != "none") new_array.push(start);
        // letrium v
        start = $("#user_tab_" + start_sys).next().find("a").attr('usr_id');
        // letrium v END
        start_sys = replaceJquerySpecialChars(start);
        if (start_sys == "") break;
        if ($("#user_tab_" + start_sys).css("display") != "none") sum -= 0 - $("#user_tab_" + start_sys).css("width").slice(0, -2) - 7
    }
    if (new_array.length !=
        0) {
        var len = visible_tabs.length;
        for (i = 0; i < len; i++) visible_tabs.pop();
        visible_tabs = new_array
    }
    new_array = []
}

function setClosedTab() {
    var now = new Date;
    var tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
    var text = "";
    $("ul.tabs.tabs1 li.t1.closed").each(function () {
        if (text != "") text += ",";
        text += $("#" + this.id + " a:first").attr('usr_id')
    });
    $.cookie("closed_tabs", text, {
        expires: tomorrow
    })
}

function closeTab(elem) {
    $(elem.parentElement.parentElement).addClass("closed");
    $(elem.parentElement).removeClass("blinking");
    $(elem.parentElement).css("background-color", "");
    clearUser();
    first_user = "all";
    setClosedTab();
    $("ul.tabs.tabs1").css("margin-left", "-40px");
    first_user = "all";
    checkArrow()
}

function select_tab(user) {
    if (user != "" && user != null && user != undefined && user != "all") {
        var user_sys = replaceJquerySpecialChars(user);
        $(".daddy-shoutbox-list").hide();
        $("#daddy-shoutbox-list_user_" + user_sys).show();
        $("#daddy-shoutbox-list_user_" + user_sys).scrollTop($("#daddy-shoutbox-list_user_" + user_sys)[0].scrollHeight);
        $("ul.tabs.tabs1 li").removeClass("tab-current");
        $("#user_tab_" + user_sys).addClass("tab-current");
        $("ul.tabs.tabs1").css("margin-left", "-40px");
        first_user = "all";
        checkArrow();
        if (visible_tabs.length !=
            0)
            while (jQuery.inArray(user, visible_tabs) == -1) $("#move_right").click();
        $("#user_tab_" + user_sys + " a:first").removeClass("blinking");
        $("#user_tab_" + user_sys + " a:first").css("background-color", "");
        $("#mess").focus()
    } else {
        clearUser();
        first_user = "all"
    }
}

function users_list(response) {
    var select_user = $("#for_user").val();
    if (response != undefined && response.usr_name != undefined) {
        count_user++;
        if (response.usr_name == select_user) var string = '<div class="shoutbox-list-selectuser" id="user-list-' + response.usr_name + '" onclick="getUser(\'user-list-' + response.usr_name + "')\" >" + '<span class="shoutbox-list-nick" usr_id="'+response.usr_name+'">' + response.display_usr_name + "</span>" + "</div>";
        else var string = '<div class="shoutbox-list-user" id="user-list-' + response.usr_name + '" onclick="getUser(\'user-list-' +
            response.usr_name + "')\" >" + '<span class="shoutbox-list-nick" usr_id="'+response.usr_name+'">' + response.display_usr_name + "</span>" + "</div>"
    }
    return string
}

function success(response, status) {
    if (status == "success") {
        $("#daddy-shoutbox-response-load").hide();
        $("#daddy-shoutbox-response-accept").css("display", "inline");
        if (response.length) {
            $("#daddy-user-list").empty();
            var select_user = $("#for_user").attr("value");
            var select_user_sys = replaceJquerySpecialChars(select_user);
            for (i = 0; i < response.length; i++) {
            //letriium tanya
            	if (response[i].nickname == myCurrentUser || response[i].foruser == myCurrentUser || response[i].foruser == 'all') {
           			var user_tab_name = (response[i].nickname == myCurrentUser) ? response[i].foruser : response[i].nickname ;
                	if (response[i].foruser == 'all') $("#daddy-shoutbox-list").append(prepare(response[i]));
                	else $("#daddy-shoutbox-list_user_" + user_tab_name).append(prepare(response[i]));
                }
            //end letrium
            	$("#daddy-user-list").append(users_list(response[i]));
                $("#list-" + count).fadeIn("fast");
                if (response[i].id != undefined) {
                    lastTime = response[i].id;
                    $("#mes_time").attr("value", lastTime)
                }
            }
        }
        $("#mess").attr("value", "");
        $("#list-" + count).fadeIn("fast");
        $("#mess").removeAttr("readonly", "");
        $("#mess").css("background-color", "");
        $("#mess").focus();
        timeoutID = setTimeout(refresh, 8E3)
    }
    if (select_user_sys != "all" && select_user_sys != "") $("#daddy-shoutbox-list_user_" + select_user_sys).scrollTop($("#daddy-shoutbox-list_user_" + select_user_sys)[0].scrollHeight);
    else $("#daddy-shoutbox-list").scrollTop($("#daddy-shoutbox-list")[0].scrollHeight);
    lastJsonLen = 0
}

function validate_chat(formData, jqForm, options) {
    var mess = $("#mess");
    messVal = jQuery.trim($(mess).val());
    if (messVal == "") {
        $(mess).val(messVal);
        return false
    }
    $(mess).attr("readonly", "true");
    $(mess).css("background-color", "rgb(220, 220, 220)");
    for (var i = 0; i < formData.length; i++)
        if (!formData[i].value) {
            $(mess).removeAttr("readonly", "").css("background-color", "");
            return false
        }
    $("#daddy-shoutbox-response-accept").hide();
    $("#daddy-shoutbox-response-load").css("display", "inline");
    clearTimeout(timeoutID)
}

function refresh() {
    myTTtop = $("#myChat").css("top");
    myTTleft = $("#myChat").css("left");
    myCollapsed = $("#myChat").attr("collapsed");
    $.getJSON("index.php?module=let_Chat&action=backend&to_pdf=true&task=view&time=" + lastTime + "&collapsed=" + myCollapsed, function (json) {
        if (json.length) {
            $("#daddy-user-list").empty();
            var select_user = $("#for_user").attr("value");
            for (i = 0; i < json.length; i++) {
                if (typeof json[i] == "undefined") continue;
                if (json[i].status == "success") continue;
                var prepare_text = prepare(json[i]);
                if (prepare_text !=
                    undefined && prepare_text != null && prepare_text != "")
                    if (json[i].nickname != undefined && json[i].foruser != undefined) {
                        var for_all = false;
                        if (json[i].foruser == "all") for_all = true;
                        // letrium v
                        if (json[i].nickname != myCurrentUser) {
                        	otherUser = json[i].nickname;
                        	display_name = json[i].display_from_name;
                        }
                        else {
                        	otherUser = json[i].foruser;
                        	display_name = json[i].display_to_name;
                        }
                        // letrium v END
                        var otherUser_sys = replaceJquerySpecialChars(otherUser);
                        if (!for_all) {
                            if ($("#user_tab_" + otherUser_sys).hasClass("closed")) {
                                $("#move_left").click();
                                $("#user_tab_" + otherUser_sys).removeClass("closed");
                                count_visible_tabs();
                                if (visible_tabs.length != 0)
                                    while (jQuery.inArray($("ul.tabs.tabs1 li.t1.tab-current a:first").attr('usr_id'),
                                        visible_tabs) == -1) $("#move_right").click();
                                setClosedTab()
                            }
                            if ($("#user_tab_" + otherUser_sys).length == 0) {
                                $("ul.tabs.tabs1").append('<li class="t1" id="user_tab_' + otherUser + '" ><a usr_id="'+otherUser+'">' + display_name + '<img src="modules/let_Chat/images/close.png" style="width:10px; top:0px;" onClick = "closeTab(this);" /></a></li>');
                                $("#user_tab_" + otherUser_sys).click(function () {
                                    var curr_id = this.id;
                                    curr_id = replaceJquerySpecialChars(curr_id);
                                    thisUser = $("#" + curr_id + " a:first").attr('usr_id');
                                    if (!$("#" + curr_id).hasClass("closed")) {
                                        getUser_mix("user-list-" +
                                            thisUser);
                                            
                                        select_tab(thisUser)
                                    }
                                    setLastRead()
                                });
                                $("#daddy-shoutbox-list").after('<div class="daddy-shoutbox-list" id="daddy-shoutbox-list_user_' + otherUser + '" style="overflow: auto; height: 230px; display:none;"></div>')
                            }
                            $("#daddy-shoutbox-list_user_" + otherUser_sys).append(prepare_text);
                            var mytime = $("#daddy-shoutbox-list_user_" + otherUser_sys + " div:last span:first").text();
                            if (arr_last_read_users.length == 0) {
                                var last_read_all_users = $.cookie("last_read") != null ? $.cookie("last_read") : "all#00:00:00";
                                arr_last_read =
                                    last_read_all_users.split(";");
                                var tmp_arr = new Array;
                                for (l = 0; l < arr_last_read.length; l++) {
                                    tmp_arr = arr_last_read[l].split("#");
                                    arr_last_read_users.push(tmp_arr[0]);
                                    arr_last_read_times.push(tmp_arr[1])
                                }
                                tmp_arr = []
                            }
                            var last_read = "00:00:00";
                            var ind = jQuery.inArray(otherUser, arr_last_read_users);
                            if (ind != -1) last_read = arr_last_read_times[ind];
                            var from_me = false;
                            if ($("#daddy-shoutbox-list_user_" + otherUser_sys + " div:last span.shoutbox-list-nick span").hasClass("isay")) from_me = true;
                            if (last_read < mytime && !from_me) {
                                if (!$("#user_tab_" +
                                    otherUser_sys).hasClass("tab-current")) $("#user_tab_" + otherUser_sys + " a").addClass("blinking");
                                if ($("#myChat").attr("collapsed") == "true") {
                                    stop_blink();
                                    start_blink()
                                }
                            }
                            if ($("#daddy-shoutbox-list_user_" + otherUser_sys + " div").length > display_num) $("#daddy-shoutbox-list_user_" + otherUser_sys + " div:first").remove()
                        } else {
                            $("#daddy-shoutbox-list").append(prepare_text);
                            if ($("#daddy-shoutbox-list div").length > display_num) $("#daddy-shoutbox-list div:first").remove()
                        }
                    } else {
                        $("#daddy-shoutbox-list").append(prepare_text);
                        if ($("#daddy-shoutbox-list div").length > display_num) $("#daddy-shoutbox-list div:first").remove()
                    }
                $("#daddy-user-list").append(users_list(json[i]));
                $("#list-" + count).fadeIn("fast");
                if (typeof json[i].id != "undefined") {
                    lastTime = json[i].id;
                    $("#mes_time").attr("value", lastTime)
                }
            }
            if (initLoad) {
                if ($.cookie("closed_tabs") != null && $.cookie("closed_tabs") != "") {
                    var closed_tabs = $.cookie("closed_tabs").split(",");
                    for (var l = 0; l < closed_tabs.length; l++)
                        if (!$("#user_tab_" + closed_tabs[l] + " a:first").hasClass("blinking")) $("#user_tab_" +
                            closed_tabs[l]).addClass("closed")
                }
                initLoad = false;
                if ($.cookie("to_user") == null || jQuery.trim($.cookie("to_user")) == "" || $.cookie("to_user") == "all") clearUser();
                else getUser("user-list-" + $.cookie("to_user"))
            }
            if (lastJsonLen == 1) {
                var thisUser = $("ul.tabs.tabs1 li.t1.tab-current a:first").attr('usr_id');
                var thisUser_sys = replaceJquerySpecialChars(thisUser);
                if (thisUser != "all" && thisUser != "") $("#daddy-shoutbox-list_user_" + thisUser_sys).scrollTop($("#daddy-shoutbox-list_user_" + thisUser_sys)[0].scrollHeight);
                else $("#daddy-shoutbox-list").scrollTop($("#daddy-shoutbox-list")[0].scrollHeight);
                checkArrow();
                lastJsonLen = 0
            }
            var selectedUser = getSelectedUser();
            if (selectedUser != "") getUser_mix("user-list-" + selectedUser)
        }
    });
    if (arguments[0] != "without") timeoutID = setTimeout(refresh, 8E3)
}

function resetColor() {
    $(".blinking").css("background-color", "")
}

function checkArrow() {
    count_visible_tabs();
    blinkArrow(false, "left");
    blinkArrow(false, "right");
    var all_users = new Array;
    $("ul.tabs.tabs1 li").each(function () {
        var curr_id = this.id;
        curr_id = replaceJquerySpecialChars(curr_id);
        all_users.push($("#" + curr_id + " a").attr('usr_id'))
    });
    if (typeof($(".blinking:first").attr('usr_id')) != 'undefined' && $(".blinking:first").attr('usr_id') != "" && jQuery.inArray($(".blinking:first").attr('usr_id'), all_users) < jQuery.inArray(visible_tabs[0], all_users)) blinkArrow(true, "left");
    if (typeof($(".blinking:last").attr('usr_id')) != 'undefined' && $(".blinking:last").attr('usr_id') != "" && jQuery.inArray($(".blinking:last").attr('usr_id'), all_users) >
        jQuery.inArray(visible_tabs[visible_tabs.length - 1], all_users)) blinkArrow(true, "right");
    all_users = []
}

function blinkArrow(isBlink, who) {
    var imgName = "";
    var imgOpacity = "";
    if (isBlink) {
        imgName = "_blink";
        imgOpacity = "0.8"
    }
    var imgFullName = "arrow_" + who + imgName + ".png";
    $("#move_" + who).attr("src", "modules/let_Chat/images/" + imgFullName).css("opacity", imgOpacity)
}

function replaceJquerySpecialChars(text) {
	if (typeof(text) == 'undefined') return '';
    var specialChars = ["#", ";", "&", ",", ".", "+", "*", "~", "'", ":", '"', "!", "^", "$", "[", "]", "(", ")", "=", ">", "|", "/"];
    var len = specialChars.length;
    for (count = 0; count < len; count++) text = text.replace(specialChars[count], "\\" + specialChars[count]);
    return text
}
SUGAR.util.doWhen("typeof $ != 'undefined'", function () {
    if (loaded == true) return;
    var options = {
        dataType: "json",
        beforeSubmit: validate_chat,
        success: success
    };
    $("#daddy-shoutbox-form").ajaxForm(options);
    $("#user_tab_all").click(function () {
        setLastRead();
        clearUser();
        first_user = "all"
    });
    first_user = replaceJquerySpecialChars(first_user);
    $("#move_right").click(function () {
        var shift_ul = 0;
        var start = first_user;
        var sum = $("#user_tab_" + start).css("width").slice(0, -2) - 0 + 7;
        while (sum < 310) {
            start = $("#user_tab_" + start).next().find("a").attr('usr_id');
            start = replaceJquerySpecialChars(start);
            if (start == "") break;
            if ($("#user_tab_" + start).css("display") != "none") sum -= 0 - $("#user_tab_" + start).css("width").slice(0, -2) - 7
        }
        if (start != "") {
            first_user = start;
            shift_ul += $("ul.tabs.tabs1").css("margin-left").slice(0, -2) - sum + 6 + ($("#user_tab_" + first_user).css("width").slice(0, -2) - 0)
        } else shift_ul = $("ul.tabs.tabs1").css("margin-left").slice(0, -2);
        $("ul.tabs.tabs1").css("margin-left", shift_ul + "px");
        checkArrow()
    });
    $("#move_left").click(function () {
        var shift_ul = 0;
        var start =
            first_user;
        var sum = 0;
        while (sum < 320) {
            start = $("#user_tab_" + start).prev().find("a").attr('usr_id');
            start = replaceJquerySpecialChars(start);
            if (start == "") break;
            if ($("#user_tab_" + start).css("display") != "none") sum -= 0 - $("#user_tab_" + start).css("width").slice(0, -2) - 7
        }
        if (start != "") {
            first_user = $("#user_tab_" + start).next().find("a").attr('usr_id');
            start = replaceJquerySpecialChars(start);
            shift_ul = $("ul.tabs.tabs1").css("margin-left").slice(0, -2) - 0 - 6 + sum - ($("#user_tab_" + start).css("width").slice(0, -2) - 0)
        } else {
            first_user = "all";
            shift_ul = -40
        } if (shift_ul > -40) shift_ul = -40;
        $("ul.tabs.tabs1").css("margin-left", shift_ul + "px");
        checkArrow()
    });
    setInterval(function () {
        $(".blinking").css("background-color", "#DDD3D3");
        anim = setTimeout(resetColor, 400)
    }, 800);
    timeoutID = setTimeout(refresh, 10)
});
