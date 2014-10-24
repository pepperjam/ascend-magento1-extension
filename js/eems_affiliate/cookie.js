(function () {
    var DURATION = 45;  // days
    var MILLISECONDS_IN_DAYS = 1000 * 60 * 60 * 24;
    var COOKIE_NAME = 'source';
	var query = location.search.substring(1);
    var source = findAffiliateField(query, "source");

    if (source) {
        // set or update the cookie with the value from the current query string
        setCookie("source", source, DURATION);
    } else {
        // delete the cookie if it exists
        var cookie = getCookie(COOKIE_NAME);
        if (cookie) {
            setCookie(COOKIE_NAME, cookie[COOKIE_NAME], -1);
        }
    }

    /**
     *
     * @param cname name of the cookie
     * @param cvalue value assigned to cookie
     * @param exdays duration of the cookie in days
     */
    function setCookie(cname, cvalue, exdays)
    {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * MILLISECONDS_IN_DAYS));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    /**
     *
     * @param cname name of the cookie
     * @returns object | null object with the field and value or null if not found
     */
    function getCookie(cname)
    {
        var cookies = document.cookie.split(';');
        for (i = 0; i < cookies.length; i++) {
            var keyPair = cookies[i].split('=');
            var field = keyPair[0].trim();
            var value = keyPair[1].trim();

            if (field === cname) {
                var cookie = {};
                cookie[field] = value;

                return cookie;
            }
        }

        return null;
    }

    /**
     *
     * @param query query string  minus the '?'
     * @param field query string field to search for
     * @returns string | null value of the query string field or null if the field isn't there
     */
    function findAffiliateField(query, field)
    {
        if (!query || (query === ""))
            return null;

        var start = query.substring(field + "=");
        if (start) {
            var keyPair = start.split("=");
            return keyPair[1] || null;
        } else {
            return null;
        }
    }
}(document));
