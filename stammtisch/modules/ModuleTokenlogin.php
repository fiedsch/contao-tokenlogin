<?php

/**
 * @copyright  Andreas Fieger 2015
 * @author     Andreas Fieger (https://github.com/fiedsch)
 * @package    Tests / POC / Misc.
 *
 * Module to allow Login with a token allone (as opposed to username+password).
 * To achieve this we do the following: username and token are semantically swapped
 * as
 *  - we can not have one username having multiple passwords.
 *  - but we can have different user names always having the same password!
 *
 * This fixed password will be the class constant TOKENUSERPASSWORD.
 *
 * The token which technically is the username will serve as the password.
 *
 * We have a special login form that takes care of these changes. It does not have
 * a password field. This (POST-)value will be set here.
 *
 * For the module to work we need to have a method registered for the importUser hook
 * that creates a new member. See classes/MyHooks::importFromTokenlist() for
 * an example.
 */
class ModuleTokenlogin extends \ModuleLogin {

    /**
     * the pseudo password
     */
    const TOKENUSERPASSWORD = "tokenuser";

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_tokenlogin_1cl';


    public function generate() {

        if (TL_MODE == 'BE') {

            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['tokenlogin'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();

        }

        // Login: there is no password field in our login form.
        // We will set this here so the regular login process will be happy.

        if (\Input::post('FORM_SUBMIT') == 'tl_login') {
            \Input::setPost('password', self::TOKENUSERPASSWORD);
        }
        // Logout: no special action needed
        /*
        if (\Input::post('FORM_SUBMIT') == 'tl_logout') { }
        */

        return parent::generate();

    }

    /**
     * Generate the module.
     */
    protected function compile() {

        parent::compile();

        // use a different template

        if (!FE_USER_LOGGED_IN) {

            $this->strTemplate = ($this->cols > 1) ? 'mod_tokenlogin_2cl' : 'mod_tokenlogin_1cl';

            $this->Template->setName($this->strTemplate);

        }

    }

}
