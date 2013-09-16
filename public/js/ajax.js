function request( url, params, id, async, callback ){
    async = ( typeof async == 'boolean' ? async : true );

    $.ajax({
        url: url,
        cache: false,
        type: 'POST',
        data: params,
        dataType: 'html',
        async: async,
        success: function( d ){
            if (id != "" && id != undefined && id != null) {
                $( "#" + id ).html(d);
            }

            if (typeof callback == 'function') {
                callback(d);
            }
        }
    });
}