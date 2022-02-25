<?php

/**
 * OpenSSH Formatted EC Key Handler
 *
 * PHP version 5
 *
 * Place in $HOME/.ssh/authorized_keys
 *
 * @category  Crypt
 * @package   EC
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2015 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace WPMailSMTP\Vendor\phpseclib3\Crypt\EC\Formats\Keys;

use WPMailSMTP\Vendor\ParagonIE\ConstantTime\Base64;
use WPMailSMTP\Vendor\phpseclib3\Math\BigInteger;
use WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings;
use WPMailSMTP\Vendor\phpseclib3\Crypt\Common\Formats\Keys\OpenSSH as Progenitor;
use WPMailSMTP\Vendor\phpseclib3\Crypt\EC\BaseCurves\Base as BaseCurve;
use WPMailSMTP\Vendor\phpseclib3\Exception\UnsupportedCurveException;
use WPMailSMTP\Vendor\phpseclib3\Crypt\EC\Curves\Ed25519;
/**
 * OpenSSH Formatted EC Key Handler
 *
 * @package EC
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
abstract class OpenSSH extends \WPMailSMTP\Vendor\phpseclib3\Crypt\Common\Formats\Keys\OpenSSH
{
    use Common;
    /**
     * Supported Key Types
     *
     * @var array
     */
    protected static $types = ['ecdsa-sha2-nistp256', 'ecdsa-sha2-nistp384', 'ecdsa-sha2-nistp521', 'ssh-ed25519'];
    /**
     * Break a public or private key down into its constituent components
     *
     * @access public
     * @param string $key
     * @param string $password optional
     * @return array
     */
    public static function load($key, $password = '')
    {
        $parsed = parent::load($key, $password);
        if (isset($parsed['paddedKey'])) {
            $paddedKey = $parsed['paddedKey'];
            list($type) = \WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::unpackSSH2('s', $paddedKey);
            if ($type != $parsed['type']) {
                throw new \RuntimeException("The public and private keys are not of the same type ({$type} vs {$parsed['type']})");
            }
            if ($type == 'ssh-ed25519') {
                list(, $key, $comment) = \WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::unpackSSH2('sss', $paddedKey);
                $key = \WPMailSMTP\Vendor\phpseclib3\Crypt\EC\Formats\Keys\libsodium::load($key);
                $key['comment'] = $comment;
                return $key;
            }
            list($curveName, $publicKey, $privateKey, $comment) = \WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::unpackSSH2('ssis', $paddedKey);
            $curve = self::loadCurveByParam(['namedCurve' => $curveName]);
            $curve->rangeCheck($privateKey);
            return ['curve' => $curve, 'dA' => $privateKey, 'QA' => self::extractPoint("\0{$publicKey}", $curve), 'comment' => $comment];
        }
        if ($parsed['type'] == 'ssh-ed25519') {
            if (\WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::shift($parsed['publicKey'], 4) != "\0\0\0 ") {
                throw new \RuntimeException('Length of ssh-ed25519 key should be 32');
            }
            $curve = new \WPMailSMTP\Vendor\phpseclib3\Crypt\EC\Curves\Ed25519();
            $qa = self::extractPoint($parsed['publicKey'], $curve);
        } else {
            list($curveName, $publicKey) = \WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::unpackSSH2('ss', $parsed['publicKey']);
            $curveName = '\\WPMailSMTP\\Vendor\\phpseclib3\\Crypt\\EC\\Curves\\' . $curveName;
            $curve = new $curveName();
            $qa = self::extractPoint("\0" . $publicKey, $curve);
        }
        return ['curve' => $curve, 'QA' => $qa, 'comment' => $parsed['comment']];
    }
    /**
     * Returns the alias that corresponds to a curve
     *
     * @return string
     */
    private static function getAlias(\WPMailSMTP\Vendor\phpseclib3\Crypt\EC\BaseCurves\Base $curve)
    {
        self::initialize_static_variables();
        $reflect = new \ReflectionClass($curve);
        $name = $reflect->getShortName();
        $oid = self::$curveOIDs[$name];
        $aliases = \array_filter(self::$curveOIDs, function ($v) use($oid) {
            return $v == $oid;
        });
        $aliases = \array_keys($aliases);
        for ($i = 0; $i < \count($aliases); $i++) {
            if (\in_array('ecdsa-sha2-' . $aliases[$i], self::$types)) {
                $alias = $aliases[$i];
                break;
            }
        }
        if (!isset($alias)) {
            throw new \WPMailSMTP\Vendor\phpseclib3\Exception\UnsupportedCurveException($name . ' is not a curve that the OpenSSH plugin supports');
        }
        return $alias;
    }
    /**
     * Convert an EC public key to the appropriate format
     *
     * @access public
     * @param \phpseclib3\Crypt\EC\BaseCurves\Base $curve
     * @param \phpseclib3\Math\Common\FiniteField\Integer[] $publicKey
     * @param array $options optional
     * @return string
     */
    public static function savePublicKey(\WPMailSMTP\Vendor\phpseclib3\Crypt\EC\BaseCurves\Base $curve, array $publicKey, array $options = [])
    {
        $comment = isset($options['comment']) ? $options['comment'] : self::$comment;
        if ($curve instanceof \WPMailSMTP\Vendor\phpseclib3\Crypt\EC\Curves\Ed25519) {
            $key = \WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::packSSH2('ss', 'ssh-ed25519', $curve->encodePoint($publicKey));
            if (isset($options['binary']) ? $options['binary'] : self::$binary) {
                return $key;
            }
            $key = 'ssh-ed25519 ' . \base64_encode($key) . ' ' . $comment;
            return $key;
        }
        $alias = self::getAlias($curve);
        $points = "\4" . $publicKey[0]->toBytes() . $publicKey[1]->toBytes();
        $key = \WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::packSSH2('sss', 'ecdsa-sha2-' . $alias, $alias, $points);
        if (isset($options['binary']) ? $options['binary'] : self::$binary) {
            return $key;
        }
        $key = 'ecdsa-sha2-' . $alias . ' ' . \base64_encode($key) . ' ' . $comment;
        return $key;
    }
    /**
     * Convert a private key to the appropriate format.
     *
     * @access public
     * @param \phpseclib3\Math\BigInteger $privateKey
     * @param \phpseclib3\Crypt\EC\Curves\Ed25519 $curve
     * @param \phpseclib3\Math\Common\FiniteField\Integer[] $publicKey
     * @param string $password optional
     * @param array $options optional
     * @return string
     */
    public static function savePrivateKey(\WPMailSMTP\Vendor\phpseclib3\Math\BigInteger $privateKey, \WPMailSMTP\Vendor\phpseclib3\Crypt\EC\BaseCurves\Base $curve, array $publicKey, $password = '', array $options = [])
    {
        if ($curve instanceof \WPMailSMTP\Vendor\phpseclib3\Crypt\EC\Curves\Ed25519) {
            if (!isset($privateKey->secret)) {
                throw new \RuntimeException('Private Key does not have a secret set');
            }
            if (\strlen($privateKey->secret) != 32) {
                throw new \RuntimeException('Private Key secret is not of the correct length');
            }
            $pubKey = $curve->encodePoint($publicKey);
            $publicKey = \WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::packSSH2('ss', 'ssh-ed25519', $pubKey);
            $privateKey = \WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::packSSH2('sss', 'ssh-ed25519', $pubKey, $privateKey->secret . $pubKey);
            return self::wrapPrivateKey($publicKey, $privateKey, $password, $options);
        }
        $alias = self::getAlias($curve);
        $points = "\4" . $publicKey[0]->toBytes() . $publicKey[1]->toBytes();
        $publicKey = self::savePublicKey($curve, $publicKey, ['binary' => \true]);
        $privateKey = \WPMailSMTP\Vendor\phpseclib3\Common\Functions\Strings::packSSH2('sssi', 'ecdsa-sha2-' . $alias, $alias, $points, $privateKey);
        return self::wrapPrivateKey($publicKey, $privateKey, $password, $options);
    }
}
