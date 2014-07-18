<?php
/**
 * @package		solo
 * @copyright	2014 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Solo\Application;

use Awf\Application\Application;
use Awf\Download\Download;
use Awf\Encrypt\Totp;
use Awf\Uri\Uri;
use Awf\User\Authentication;

class UserAuthenticationGoogle extends Authentication
{
	/**
	 * Is this user authenticated by this object? The $params array contains at least one key, 'password'.
	 *
	 * @param   array   $params    The parameters used in the authentication process
	 *
	 * @return  boolean  True if the user is authenticated (or this plugin doesn't apply), false otherwise
	 */
	public function onAuthentication($params = array())
	{
		$result = true;

		$userParams = $this->user->getParameters();

		if ($userParams->get('tfa.method', 'none') == 'google')
		{
			$result = false;

			$secret = isset($params['secret']) ? $params['secret'] : '';

			if (!empty($secret))
			{
				$result = $this->validateGoogleOTP($secret);
			}
		}

		return $result;
	}

	public function onTfaSave($tfaParams)
	{
		$tfaMethod = isset($tfaParams['method']) ? $tfaParams['method'] : '';

		if ($tfaMethod == 'google')
		{
			// The Google Authenticator key set by the user in the form
			$newKey = isset($tfaParams['google']) ? $tfaParams['google'] : '';
			// The Google Authenticator key in the user object
			$oldKey = $this->user->getParameters()->get('tfa.google', '');
			// The Google Authenticator generated secret code given in the form
			$secret = isset($tfaParams['secret']) ? $tfaParams['secret'] : '';
			// What was the old TFA method?
			$oldTfaMethod = $this->user->getParameters()->get('tfa.method');

			if (($oldTfaMethod == 'google') && ($newKey == $oldKey))
			{
				// We had already set up Google Authenticator and the code is unchanged. No change performed here.
				return true;
			}
			else
			{
				// Safe fallback until we can verify the new yubikey
				$this->user->getParameters()->set('tfa', null);
				$this->user->getParameters()->set('tfa.method', 'none');

				if (!empty($secret) && $this->validateGoogleOTP($secret, $newKey))
				{
					$this->user->getParameters()->set('tfa.method', 'google');
					$this->user->getParameters()->set('tfa.google', $newKey);
				}
			}
		}

		return true;
	}

	/**
	 * Validates a Google Authenticator key
	 *
	 * @param   string  $otp  The OTP generated by Google Authenticator
	 * @param   string  $key  The TOTP key (base32 encoded)
	 *
	 * @return  boolean  True if it's a valid OTP
	 */
	public function validateGoogleOTP($otp, $key = null)
	{
		// Create a new TOTP class with Google Authenticator compatible settings
		$totp = new Totp(30, 6, 10);

		// Get the key if none is defined
		if (empty($key))
		{
			$key = $this->user->getParameters()->get('tfa.google', '');
		}

		// Check the code
		$code = $totp->getCode($key);

		$check = $code == $otp;

		/*
		 * If the check fails, test the previous 30 second slot. This allow the
		 * user to enter the security code when it's becoming red in Google
		 * Authenticator app (reaching the end of its 30 second lifetime)
		 */
		if (!$check)
		{
			$time = time() - 30;
			$code = $totp->getCode($key, $time);
			$check = $code == $otp;
		}

		/*
		 * If the check fails, test the next 30 second slot. This allows some
		 * time drift between the authentication device and the server
		 */
		if (!$check)
		{
			$time = time() + 30;
			$code = $totp->getCode($key, $time);
			$check = $code == $otp;
		}

		return $check;
	}
}