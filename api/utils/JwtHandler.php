<?php
class JwtHandler {
    protected $secret_key = 'your_secret_key';
    protected $algorithm = 'HS256';

    public function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => $this->algorithm]);
        $header = $this->base64UrlEncode($header);
        $payload = json_encode($payload);
        $payload = $this->base64UrlEncode($payload);
        $signature = hash_hmac('sha256', "$header.$payload", $this->secret_key, true);
        $signature = $this->base64UrlEncode($signature);
        return "$header.$payload.$signature";
    }

    public function decode($token) {
        list($header, $payload, $signature) = explode('.', $token);
        $valid = hash_hmac('sha256', "$header.$payload", $this->secret_key, true);
        $valid = $this->base64UrlEncode($valid);
        if ($signature === $valid) {
            return json_decode($this->base64UrlDecode($payload), true);
        }
        return null;
    }

    protected function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function base64UrlDecode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
?>