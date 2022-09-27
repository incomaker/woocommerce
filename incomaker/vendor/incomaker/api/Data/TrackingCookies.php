<?php

namespace Incomaker\Api\Data;

/**
 * Cookies from Incomaker tracking system
 */
class TrackingCookies {

    const INCOMAKER_COOKIE_SESSION_TEMP = 'inco_session_temp_browser'; //one hour session
    const INCOMAKER_COOKIE_PERM_ID = 'incomaker_p'; //perm_id
    const INCOMAKER_COOKIE_CONTACT_ID = 'incomaker_k'; //contact id
    const INCOMAKER_COOKIE_TEMP = 'tempUUID'; //sessionId to the end of session (browser is closed etc)
    const INCOMAKER_COOKIE_SESSION_KEY = '_____tempSessionKey_____'; //3 year cookie like perm_id

    /**
     * Unique id of some contact
     * @return type
     */

    static function getPermId() {
        return $_COOKIE[self::INCOMAKER_COOKIE_PERM_ID];
    }

    /**
     * One hour session
     * @return type
     */
    static function getSessionId() {
        return $_COOKIE[self::INCOMAKER_COOKIE_SESSION_TEMP];
    }

    /**
     * Contact id session
     * @return type
     */
    static function getContactId() {
        return $_COOKIE[self::INCOMAKER_COOKIE_CONTACT_ID];
    }

    /**
     * Cookie die after page or browser is closed. short living session
     * @return type
     */
    static function getPageSessionId() {
        return $_COOKIE[self::INCOMAKER_COOKIE_TEMP];
    }

    /**
     * 3 year cookie for web
     * @return type
     */
    static function getPageLongSessionId() {
        return $_COOKIE[self::INCOMAKER_COOKIE_SESSION_KEY];
    }

}
