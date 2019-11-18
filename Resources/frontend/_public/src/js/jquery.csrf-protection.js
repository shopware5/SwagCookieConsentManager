(function ($, window) {
    'use strict';

    window.CSRF.requestToken = function () {
        var me = this;

        if (window.StateManager.hasCookiesAllowed() || window.cookieRemoval === 1) {
            $.ajax({
                url: window.csrfConfig.generateUrl,
                success: function(response, status, xhr) {
                    me.saveToken(xhr.getResponseHeader('x-csrf-token'));
                    $.publish('plugin/swCsrfProtection/requestToken', [ me, me.getToken() ]);
                    me.afterInit();
                }
            });
        }
    };
})(jQuery, window);