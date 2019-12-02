;(function($, window) {
    'use strict';

    $.overridePlugin('swCookiePermission', {
        init: function () {
            /**
             * Selector of the cookie consent manager.
             *
             * @property cookieConsentManagerSelector
             * @type {string}
             */
            this.opts.cookieConsentManagerSelector = '#cookie-consent';

            /**
             * Selector of the hidden input element that contains a cookie group's name.
             * Will be used to check if the cookie preferences are up to date by checking against a hash.
             *
             * @property cookieGroupNameSelector
             * @type {String}
             */
            this.opts.cookieGroupNameSelector = '.cookie-consent--group-name';

            /**
             * Selector of the hidden input element which contains the name of a cookie.
             * Will be used to check if the cookie preferences are up to date by checking against a hash.
             *
             * @property cookieNameSelector
             * @type {String}
             */
            this.opts.cookieNameSelector = '.cookie-consent--cookie-name';

            this.superclass.init.apply(this, arguments);
        },

        displayCookiePermission: function(callback) {
            if (window.cookieRemoval === 1) {
                if ($.getCookie('cookieDeclined')) {
                    callback(false);
                    return;
                }

                if ($.getCookie('cookiePreferences') && !this.hasPreferencesHashChanged()) {
                    callback(false);
                    return;
                }
            }

            if ((window.cookieRemoval === 2 && document.cookie.indexOf('allowCookie') !== -1)) {
                callback(false);
                return;
            }

            callback(!this.storage.getItem(this.storageKey));
        },

        /**
         * Checks if the hash for the preferences has changed. This happened e.g. when a new cookie
         * was registered by a plugin.
         *
         * @returns { boolean }
         */
        hasPreferencesHashChanged: function () {
            var preferences = JSON.parse($.getCookie('cookiePreferences')),
                currentHash = preferences.hash,
                uniqueNames = [],
                newHash;

            $(this.opts.cookieGroupNameSelector).each(function (key, group) {
                uniqueNames.push($(group).val());
            });

            $(this.opts.cookieNameSelector).each(function (key, cookie) {
                uniqueNames.push($(cookie).val());
            });

            uniqueNames.sort();
            newHash = window.btoa(JSON.stringify(uniqueNames));

            return newHash !== currentHash;
        },

        /**
         * Event handler for the acceptButton click.
         *
         * @public
         * @method onAcceptButtonClick
         */
        onAcceptButtonClick: function(event) {
            event.preventDefault();

            try {
                window.localStorage.setItem(this.storageKey, 'true');
            } catch (err) {}

            var d = new Date();
            d.setTime(d.getTime() + (180 * 24 * 60 * 60 * 1000));

            document.cookie = 'allowCookie=1; path=' + this.getBasePath() + ';expires=' + d.toGMTString() + ';';

            this.hideElement();
            this.applyActiveToPreferences();

            $.publish('plugin/swCookiePermission/onAcceptButtonClick', [this, event]);
        },

        /**
         * Event handler for the declineButton click.
         *
         * @public
         * @method onDeclineButtonClick
         */
        onDeclineButtonClick: function(event) {
            event.preventDefault();

            document.cookie = 'cookieDeclined=1; path=' + this.getBasePath() + ';';

            this.hideElement();

            $.publish('plugin/swCookiePermission/onDeclineButtonClick', [this, event]);
        },


        applyActiveToPreferences: function () {
            var cookieConsentPlugin = $(this.opts.cookieConsentManagerSelector).data('plugin_swCookieConsentManager');
            cookieConsentPlugin.buildCookiePreferences(true);
        },

        getBasePath: function () {
            return window.csrfConfig.basePath || '/';
        }
    });

})(jQuery, window);