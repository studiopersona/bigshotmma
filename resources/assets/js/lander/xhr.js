import jQuery from 'jquery'

var XHR =  (function($, w, undefined) {
    var options = {
        action: '',
        method: 'post',
        responseType: 'json',
        data: '',
        async: true
    }

    var init = function(o) {
        $.extend(options, o)

        return $.ajax({
            url: options.action,
            type: options.method,
            dataType: options.responseType,
            data: options.data,
            async: options.async
        })
    };

    return {
        init: init
    };
})(jQuery, window)

export default XHR;