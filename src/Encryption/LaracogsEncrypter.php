<?php

namespace Yab\Laracogs\Encryption;

class LaracogsEncrypter implements LaracogsEncrypterInterface
{
    /**
     * The app key or unique ID.
     *
     * @var string
     */
    protected $key;

    /**
     * The session key.
     *
     * @var string
     */
    protected $session;

    /**
     * Length of the hash to be returned.
     *
     * @var interger
     */
    protected $length;

    /**
     * Encrypted Key.
     *
     * @var string
     */
    protected $encryptionKey;

    /**
     * Bad URL characters.
     *
     * @var array
     */
    protected $specialCharactersForward;

    /**
     * Bad URL characters.
     *
     * @var array
     */
    protected $specialCharactersReversed;

    /**
     * Construct the Encrypter with the fields.
     *
     * @param string
     * @param string
     * @param int
     */
    public function __construct($key, $session)
    {
        $this->key = $key;
        $this->session = $session;
        $this->specialCharactersForward = [
            '+' => '.',
            '=' => '-',
            '/' => '~',
        ];
        $this->specialCharactersReversed = [
            '.' => '+',
            '-' => '=',
            '~' => '/',
        ];
        $this->encryptionKey = $this->get_encryption_key();
    }

    /**
     * Generates a UUID.
     *
     * @return string
     */
    public function uuid()
    {
        $uid = uniqid(null, true);

        $rawid = strtoupper(sha1(uniqid(rand(), true)));

        $uuid = substr($uid, 6, 8);
        $uuid .= '-'.substr($uid, 0, 4);
        $uuid .= '-'.substr(sha1(substr($uid, 3, 3)), 0, 4);
        $uuid .= '-'.substr(sha1(substr(time(), 3, 4)), 0, 4);
        $uuid .= '-'.strtolower(substr($rawid, 10, 12));

        return $uuid;
    }

    /**
     * Encrypt the string using your app and session keys,
     * then return the new encrypted string.
     *
     * @param string $value String to encrypt
     *
     * @return string
     */
    public function encrypt($value)
    {
        $iv = substr(md5(random_bytes(16)), 0, 16);
        $encrypted = openssl_encrypt($value, 'AES-256-CBC', $this->encryptionKey, null, $iv);

        return $this->url_encode($iv.$encrypted);
    }

    /**
     * Decrypt a string.
     *
     * @param string $value Encrypted string
     *
     * @throws Exception
     *
     * @return string
     */
    public function decrypt($value)
    {
        $decoded = $this->url_decode($value);
        $iv = substr($decoded, 0, 16);
        $encryptedValue = str_replace($iv, '', $decoded);

        return trim(openssl_decrypt($encryptedValue, 'AES-256-CBC', $this->encryptionKey, null, $iv));
    }

    /**
     * Combine two keys to get the encryption
     * key that will be used.
     *
     * @return string
     */
    protected function get_encryption_key()
    {
        return md5($this->key.$this->session);
    }

    /**
     * Encode the string to be used as a url slug.
     *
     * @param  string
     *
     * @return string
     */
    protected function url_encode($string)
    {
        return rawurlencode($this->url_base64_encode($string));
    }

    /**
     * Decode the string to be used as a url slug.
     *
     * @param  string
     *
     * @return string
     */
    protected function url_decode($string)
    {
        return $this->url_base64_decode(rawurldecode($string));
    }

    /**
     * Base 64 encode.
     *
     * @param string $string String to encode
     *
     * @return string
     */
    protected function url_base64_encode($string)
    {
        return strtr(base64_encode($string), $this->specialCharactersForward);
    }

    /**
     * Base 64 decode.
     *
     * @param string $string String to decode
     *
     * @return string
     */
    protected function url_base64_decode($string)
    {
        return base64_decode(strtr($string, $this->specialCharactersReversed));
    }
}
