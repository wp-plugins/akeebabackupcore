<?php
/**
 * @package        solo
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace Solo\Model\Json\Encapsulation;

use Akeeba\Engine\Factory;

/**
 * AES CTR 128 encapsulation
 */
class AesCtr128 extends Base
{
	/**
	 * Constructs the encapsulation handler object
	 */
	function __construct()
	{
		parent::__construct(2, 'ENCAPSULATION_AESCTR128', 'Data in AES-128 stream (CTR) mode encrypted JSON');
	}

	/**
	 * Decodes the data. For encrypted encapsulations this means base64-decoding the data, decrypting it and then JSON-
	 * decoding the result. If any error occurs along the way the appropriate exception is thrown.
	 *
	 * The data being decoded corresponds to the Request Body described in the API documentation
	 *
	 * @param   string  $serverKey  The server key we need to decode data
	 * @param   string  $data       Encoded data
	 *
	 * @return  string  The decoded data.
	 *
	 * @throws  \RuntimeException  When the server capabilities don't match the requested encapsulation
	 * @throws  \InvalidArgumentException  When $data cannot be decoded successfully
	 *
	 * @see     https://www.akeebabackup.com/documentation/json-api/ar01s02.html
	 */
	public function decode($serverKey, $data)
	{
		return Factory::getEncryption()->AESDecryptCtr($data, $serverKey, 128);
	}

	/**
	 * Encodes the data. The data is JSON encoded by this method before encapsulation takes place. Encrypted
	 * encapsulations will then encrypt the data and base64-encode it before returning it.
	 *
	 * The data being encoded correspond to the body > data structure described in the API documentation
	 *
	 * @param   string  $serverKey  The server key we need to encode data
	 * @param   mixed   $data       The data to encode, typically a string, array or object
	 *
	 * @return  string  The encapsulated data
	 *
	 * @see     https://www.akeebabackup.com/documentation/json-api/ar01s02s02.html
	 *
	 * @throws  \RuntimeException  When the server capabilities don't match the requested encapsulation
	 * @throws  \InvalidArgumentException  When $data cannot be converted to JSON
	 */
	public function encode($serverKey, $data)
	{
		return Factory::getEncryption()->AESEncryptCtr($data, $serverKey, 128);
	}
}