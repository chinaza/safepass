<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait EncLib
{
  /**
  * Generates key for AES encryption. Uses provided salt, if no salt, generate pseudo random bytes using openssl
  * Returns 32-bytes binary string
  * @param  string $password
  * @param  string|null  $salt
  * @return array
  */
  public function generateKey(string $password, string $salt = null)
  {
    // Generate a random IV
    $salt = !$salt? openssl_random_pseudo_bytes(32):$salt;
    $key = hash_pbkdf2("sha256", $password, $salt, 1000, 32, true);

    return [
      'key' => $key,
      'salt' => $salt
    ];
  }

  /**
  * Encrypts specified data using AES-256CBC
  * Returns encrypted string
  * @param  string $data
  * @param  string $key
  * @param  string|null $iv
  * @return array
  */
  public function aesEncrypt(string $data, string $key, string $iv = null)
  {
    $cipher = "AES-256-CBC";
    if (!$iv) {
      $ivlen = openssl_cipher_iv_length($cipher);
      $iv = openssl_random_pseudo_bytes($ivlen);
    }
    $ciphertext = openssl_encrypt($data, $cipher, $key, $options=0, $iv);
    return [
      'ciphertext' => $ciphertext,
      'iv' => $iv
    ];
  }

  /**
  * Generates key pair for RSA encryption.
  * Returns Array of private and public keys
  * @return array
  */
  public function generateKeyPair(){
    // Set the key parameters
    $config = array(
      "digest_alg" => "sha512",
      "private_key_bits" => 4096,
      "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    // Create the private and public key pair
    $res = openssl_pkey_new($config);

    // Extract the private key from $res to $privKey
    openssl_pkey_export($res, $privKey);

    // Extract the public key from $res to $pubKey
    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];

    return [
      'private' => $privKey,
      'public' => $pubKey,
    ];
  }

}
