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

        $this->loadLanguageFile('modules'); // also required in compile()

        if (\Input::post('FORM_SUBMIT') == 'tl_login') {

            // Check whether a token was supplied

            if (empty($_POST['username'])) {
                // adjust error message
                $_SESSION['LOGIN_ERROR'] = $GLOBALS['TL_LANG']['FMD']['logtok_emptyField'];
                $this->reload();
            }

            // Login: there is no password field in our login form.
            // We will set this here so the regular login process will be happy.

            \Input::setPost('password', self::TOKENUSERPASSWORD);
        }

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

            $this->Template->username = $GLOBALS['TL_LANG']['FMD']['toklog_token'];
            $this->Template->slabel = specialchars($GLOBALS['TL_LANG']['FMD']['toklog_slabel']);

            // adjust error messages

            if (isset($_SESSION['LOGIN_ERROR'])) {

                if ($_SESSION['LOGIN_ERROR'] === $GLOBALS['TL_LANG']['ERR']['invalidLogin']) {
                    $_SESSION['LOGIN_ERROR'] = $GLOBALS['TL_LANG']['FMD']['logtok_loginError'];
                }

                $this->Template->message = $_SESSION['LOGIN_ERROR'];
            }


            if (!empty($_SESSION['TL_ERROR'])) {
                $this->Template->message = $_SESSION['TL_ERROR'];
                $_SESSION['TL_ERROR'] = array();
            }

        } else {

            // change the "logged in as ..." message

            $this->Template->loggedInAs = sprintf($GLOBALS['TL_LANG']['FMD']['logtok_loggedInAs'], $this->User->username);

        }

    }

}
