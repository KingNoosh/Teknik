<?php
Class Cryptography
{
	function Encrypt($key, $iv, $source, $destination, $cipher)	{
		if (extension_loaded('mcrypt') === true)
		{
			if (is_file($source) === true)
			{
				$source = file_get_contents($source);
        switch($cipher)
        {
          case 'tripleDES':
            $encryptedSource=$this->TripleDesEncrypt($source,$key,$iv);
            break;
          case 'AES':
            $encryptedSource=$this->AESEncrypt($source,$key,$iv);
            break;
          default:
            $encryptedSource=$this->TripleDesEncrypt($source,$key,$iv);
            break;
        }
				if (file_put_contents($destination,$encryptedSource, LOCK_EX) !== false)
				{
					return true;
				}
				return false;
			}
			return false;
		}

		return false;
	}

	function Decrypt($key, $iv, $source, $destination, $cipher) {
		if (extension_loaded('mcrypt') === true)
		{
			if (is_file($source) === true)
			{
				$source = file_get_contents($source);
        switch($cipher)
        {
          case 'tripleDES':
            $decryptedSource=self::TripleDesDecrypt($source,$key,$iv);
            break;
          case 'AES':
            $decryptedSource=self::AESDecrypt($source,$key,$iv);
            break;
          default:
            $decryptedSource=self::TripleDesDecrypt($source,$key,$iv);
            break;
        }
				if (file_put_contents($destination,$decryptedSource, LOCK_EX) !== false)
				{
					return true;
				}
				echo "no read";
				return false;
			}
			echo "no file";
			return false;
		}
			echo "no mcrypt";

		return false;
	}

	/*
	 Apply tripleDES algorthim for encryption, append "___EOT" to encrypted file ,
	 so that we can remove it while decrpytion also padding 0's
	 */
	function TripleDesEncrypt($buffer,$key,$iv) {

			$cipher = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
			$buffer.='___EOT';
			// get the amount of bytes to pad
			$extra = 8 - (strlen($buffer) % 8);
	 		// add the zero padding
			if($extra > 0) {
			for($i = 0; $i < $extra; $i++) {
				$buffer .= '_';
				}
			}
	     	 mcrypt_generic_init($cipher, $key, $iv);
		 $result = mcrypt_generic($cipher, $buffer);
		 mcrypt_generic_deinit($cipher);
		return base64_encode($result);
	}

	/*
	 Apply tripleDES algorthim for decryption, remove "___EOT" from encrypted file ,
	 so that we can get the real data.
	 */
	function TripleDesDecrypt($buffer,$key,$iv) {
	
		   $buffer= base64_decode($buffer);
		   $cipher = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
		   mcrypt_generic_init($cipher, $key, $iv);
		   $result = mdecrypt_generic($cipher,$buffer);
	        $result=substr($result,0,strpos($result,'___EOT'));
	   	   mcrypt_generic_deinit($cipher);
	 	  return $result;
	}
  
	/*
	 Apply AES algorthim for encryption, append "___EOT" to encrypted file ,
	 so that we can remove it while decrpytion also padding 0's
	 */
	function AESEncrypt($buffer,$key,$iv) {

			$cipher = mcrypt_module_open('rijndael-256', '', 'cbc', '');
			$buffer.='___EOT';
			// get the amount of bytes to pad
			$extra = 8 - (strlen($buffer) % 8);
	 		// add the zero padding
			if($extra > 0) {
			for($i = 0; $i < $extra; $i++) {
				$buffer .= '_';
				}
			}
	     	 mcrypt_generic_init($cipher, $key, $iv);
		 $result = mcrypt_generic($cipher, $buffer);
		 mcrypt_generic_deinit($cipher);
		return base64_encode($result);
	}

	/*
	 Apply AES algorthim for decryption, remove "___EOT" from encrypted file ,
	 so that we can get the real data.
	 */
	function AESDecrypt($buffer,$key,$iv) {
	
		   $buffer= base64_decode($buffer);
		   $cipher = mcrypt_module_open('rijndael-256', '', 'cbc', '');
		   mcrypt_generic_init($cipher, $key, $iv);
		   $result = mdecrypt_generic($cipher,$buffer);
	        $result=substr($result,0,strpos($result,'___EOT'));
	   	   mcrypt_generic_deinit($cipher);
	 	  return $result;
	}
}
?>