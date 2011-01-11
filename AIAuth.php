<?php

/**
* Add this to the bottom of LocalSettings.php:
* require_once( "$IP/extensions/artcloud-mediawiki/AIAuth.php" );
* $wgAuth = new AIAuthPlugin();
* $djangoAuthURL = "http://127.0.0.1:8000/api/front/auth/";
* $djangoExistsURL = "http://127.0.0.1:8000/api/front/exists/";
*/

class AIAuthPlugin extends AuthPlugin {
	public function cleanUsername($username){
		return str_replace(" ", "_", strtolower($username));
	}
	
	/**
	 * @param $username String: username.
	 * @return bool
	 */
	public function userExists( $username ) {
		global $djangoExistsURL;
		$cleaned_username = AIAuthPlugin::cleanUsername($username);
		print "Cleaned username: $cleaned_username";
		$r = Http::get($djangoExistsURL . '?username=' . $cleaned_username);
		if($r == false){
			return false;
		}
		return $r == 'True';
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
		print 'ooo';
		$cleaned_username = AIAuthPlugin::cleanUsername($username);
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
		return true;
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
		print 'um...';
		return new AuthPluginUser( $user );
	}
}
// Copyright 2011 GORBET + BANERJEE (http://www.gorbetbanerjee.com/) Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
