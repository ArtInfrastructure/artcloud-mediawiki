<?php

/**
* Add this to the bottom of LocalSettings.php:
* require_once( "$IP/extensions/artcloud-mediawiki/AIAuth.php" );
* $wgAuth = new AIAuthPlugin();
*/

class AIAuthPlugin extends AuthPlugin {
	/**
	 * @param $username String: username.
	 * @return bool
	 */
	public function userExists( $username ) {
		return true;
	}

	/**
	 * Check if a username+password pair is a valid login.
	 * The name will be normalized to MediaWiki's requirements, so
	 * you might need to munge it (for instance, for lowercase initial
	 * letters).
	 *
	 * @param $username String: username.
	 * @param $password String: user password.
	 * @return bool
	 */
	public function authenticate( $username, $password ) {
		global $djangoAuthURL;
		$cleaned_username = strtolower($username);
		$r = Http::post($djangoAuthURL, array('postData' => wfArrayToCGI(array('username'=>$cleaned_username, 'password'=>$password))));
		if($r == false){
			return false;
		}
		return $r == 'True';
	}

	/**
	 * @return Boolean
	 */
	public function autoCreate() {
		return false;
	}

	/**
	 * @return Boolean
	 */
	public function allowPropChange( $prop = '' ) {
		return false;
	}

	/**
	 * @return bool
	 */
	public function allowPasswordChange() {
		return false;
	}

	/**
	 * @param $user User object.
	 * @param $password String: password.
	 * @return bool
	 */
	public function setPassword( $user, $password ) {
		return false;
	}

	/**
	 * @param $user User object.
	 * @return Boolean
	 */
	public function updateExternalDB( $user ) {
		return false;
	}

	/**
	 * @return Boolean
	 */
	public function canCreateAccounts() {
		return false;
	}

	/**
	 * @param $user User: only the name should be assumed valid at this point
	 * @param $password String
	 * @param $email String
	 * @param $realname String
	 * @return Boolean
	 */
	public function addUser( $user, $password, $email='', $realname='' ) {
		return false;
	}


	/**
	 * @return Boolean
	 */
	public function strict() {
		return true;
	}

	/**
	 * @param $username String: username.
	 * @return Boolean
	 */
	public function strictUserAuth( $username ) {
		return true;
	}

	/**
	 * @param $user User object.
	 * @param $autocreate Boolean: True if user is being autocreated on login
	 */
	public function initUser( &$user, $autocreate=false ) {
		# Override this to do something.
	}

	/**
	 * If you want to munge the case of an account name before the final
	 * check, now is your chance.
	 */
	public function getCanonicalName( $username ) {
		return $username;
	}
	
	/**
	 * Get an instance of a User object
	 *
	 * @param $user User
	 */
	public function getUserInstance( User &$user ) {
		return new AuthPluginUser( $user );
	}
}
