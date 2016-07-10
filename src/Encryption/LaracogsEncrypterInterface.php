<?php

namespace Yab\Laracogs\Encryption;

interface LaracogsEncrypterInterface
{
    /**
   * Encrypt a given string and
   * return the hashed value.
   *
   * @param  string
   *
   * @return string
   */
  public function encrypt($value);

  /**
   * Decrypt the given hash and
   * return the decrypted value.
   *
   * @param  string
   *
   * @return string
   */
  public function decrypt($value);

  /**
   * Generates a UUID.
   *
   * @return string
   */
  public function uuid();
}
