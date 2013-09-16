$(function(){
    var chatCounter = 0;
    var chatType    = "";

    $( "#chat" ).focus();

    $( "#submit" ).click(function(){
        var params = {
            "chat"      : encodeURIComponent($( "#chat" ).val()),
            "chat_type" : $( "#chat_type" ).val(),
        };
        request("/chat/ajax/add", params);

        $( "#chat" ).val("");
        $( "#chat" ).focus();
    });

    setInterval(function(){
        request("/chat/ajax/count", {"chat_type":$( "#chat_type" ).val()}, "", true, function(val){
            if (chatCounter != val || chatType != $("chat_type").val()) {
                chatCounter = val;
                chatType    = $("chat_type").val();

                request("/chat/ajax/messages", {"chat_type" : $( "#chat_type" ).val()}, "chat-content");
            }
        });
    }, 3000);

    request("/chat/ajax/messages", {"chat_type":"public"}, "chat-content");
});

// The calling order is important
$.getScript("/js/chat-users.js", function(data, textStatus, jqxhr){});
$.getScript("/js/chat-type.js", function(data, textStatus, jqxhr){});