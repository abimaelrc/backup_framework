$(function(){    request("/chat/ajax/available-users", {}, "chat-available-users", false, function(){        $( "[name=\"chat-users[]\"]" ).click(function(){            if ($( this ).prop("checked") === true) {                $( this ).parent().addClass("selected");            } else {                $( this ).parent().removeClass("selected");            }        });    });    $( "#chat-available-users div" ).click(function(){        if ($( "#chat_type" ).val() == 'public') {            return false;        }        if ($( this ).find( "input" ).prop( "checked" ) === true) {            var params = {                "chat_type" : $( "#chat_type" ).val(),                "users_id"  : $( this ).find( "input" ).val(),                "leader"    : $( "#created_by" ).val()            };            request("/chat/ajax/add-private-user", params);        }    });});