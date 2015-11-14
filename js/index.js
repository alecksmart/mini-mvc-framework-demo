'use strict';

var capitalizeFirstLetter = capitalizeFirstLetter || function(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
};

Namespace.create('system.common');
system.common.utils = {

    init: function() {
        $(document).ready(function() {

        });
    },

    rand: function(num) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < num; i++){
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
    },

    serializeObject: function(_o) {
        var a = _o.serializeArray();
        var o = {};
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    },

    isIE9: function() {
        if ($.browser.msie && parseInt($.browser.version.split('.')[0]) <= 9) {
            return true;
        }
        return false;
    }
};

system.common.utils.init();
