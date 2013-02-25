<?php

/**
* sm_string_encrypt()
*
* @param string $data
* @return string $encrypted
*/
function sm_string_encrypt( $data )
{
	$iv_size = mcrypt_get_iv_size( SM_DATA_ENCRYPTION_CIPHER, MCRYPT_MODE_ECB );
	$iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );

	return mcrypt_encrypt( SM_DATA_ENCRYPTION_CIPHER, SM_DATA_ENCRYPTION_KEY, $data, MCRYPT_MODE_ECB, $iv );
}

/**
* sm_string_decrypt()
*
* @param string $data
* @return string $decrypted
*/
function sm_string_decrypt( $data )
{
	$iv_size = mcrypt_get_iv_size( SM_DATA_ENCRYPTION_CIPHER, MCRYPT_MODE_ECB );
	$iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );

	return mcrypt_decrypt( SM_DATA_ENCRYPTION_CIPHER, SM_DATA_ENCRYPTION_KEY, $data, MCRYPT_MODE_ECB, $iv );
}

?>