<?php
/**
 * DokuWiki Plugin Authdomain Limitation (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  basteyyy <sebastian@34n.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class action_plugin_authdomainlimitation_signup extends DokuWiki_Action_Plugin
{

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler $controller)
    {

        $controller->register_hook('AUTH_USER_CHANGE', 'BEFORE', $this, 'handle_auth_user_change');

    }

    /**
     * [Custom event handler which performs action]
     *
     * @param Doku_Event $event event object by reference
     * @param mixed $param [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */

    public function handle_auth_user_change(Doku_Event &$event, $param)
    {

        if ($event->data['type'] !== 'create') {
            return;
        }

        $domains = array_map(function ($domain) {
            return trim($domain);
        }, explode(';', $this->getConf('_domainWhiteList')));

        $email = $event->data['params'][3];
        $checks = array(
            in_array(trim(substr(strrchr($email, "@"), 1)), $domains),
            (bool)preg_match($this->getConf('_emailRegex', '/@/'), $email),
        );

        if($this->getConf('checksAnd', true)) {
            $status = array_reduce($checks, function($a, $b) {
                return $a && $b;
            }, true);
        } else {
            $status = array_reduce($checks, function($a, $b) {
                return $a||$b;
            }, false);
        }

        if(!$status) {
            $event->preventDefault();
            $event->stopPropagation();
            $event->result = false;

            msg($this->getConf('_domainlistErrorMEssage'), -1);
        }
    }

}

// vim:ts=4:sw=4:et:
