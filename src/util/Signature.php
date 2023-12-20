<?php

namespace paraqr\payment\util;

use StephenHill\Base58;
use EllipticCurve\Curve;
use EllipticCurve\Ecdsa;
use EllipticCurve\PrivateKey;
use EllipticCurve\PublicKey;
use EllipticCurve\Point;
use EllipticCurve\Utils\Binary;
use EllipticCurve\Signature as eSignature;
use paraqr\payment\base\Singleton;

class Signature {
    use Singleton;

    /**
     * @param $message
     * @param $priKey
     * @return string
     */
    public function sign($message, $priKey) {
        $priKeyRaw = $this->base58Decode($priKey);
        $privateKey = PrivateKey::fromString(bin2hex($priKeyRaw));

        $signature = Ecdsa::sign($message, $privateKey);
        $hexRes = $signature->_toString();
        return $this->base58Encode(hex2bin($hexRes));
    }

    /**
     * @param $message
     * @param $sign
     * @param $pubKey
     * @return bool
     */
    public function verify($message, $sign, $pubKey): bool {
        $signBinary = $this->base58Decode($sign);
        $signature = eSignature::_fromString(bin2hex($signBinary));

        $pubKeyBinary = $this->base58Decode($pubKey);
        $publicKey = $this->loadBinaryPubKey($pubKeyBinary);
        return Ecdsa::verify($message, $signature, $publicKey);
    }

    /**
     * @param $data
     * @return string
     */
    public function base58Encode($data): string {
        $base58 = new Base58();
        return $base58->encode($data);
    }

    /**
     * @param $data
     * @return string
     */
    public function base58Decode($data): string {
        $base58 = new Base58();
        return $base58->decode($data);
    }

    /**
     * @param $binaryPubKey
     * @param $curve
     * @return PublicKey
     */
    public function loadBinaryPubKey($binaryPubKey, $curve = null): PublicKey {
        $curve = is_null($curve) ? Curve::$supportedCurves["secp256k1"] : $curve;
        if ($binaryPubKey[0] === "\x04") {
            $binaryPubKey = substr($binaryPubKey, 1);
        }
        $partLength = strlen($binaryPubKey) / 2;
        $x = gmp_init(bin2hex(substr($binaryPubKey, 0, $partLength)), 16);
        $y = gmp_init(bin2hex(substr($binaryPubKey, $partLength)), 16);
        return new PublicKey(new Point(Binary::intFromHex($x), Binary::intFromHex($y)), $curve);
    }
}

