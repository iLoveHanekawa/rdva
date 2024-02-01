<?php

class GiftCardController {
    public static function show(WP_REST_Request $request) {
        session_start();
        $username = 'ck_ad713bc399f8d63da81a3583057b3e7b3d0899d4';
        $password = 'cs_ee0259074bde553ce2008e6e0cd3994f99da77d5';
        $bsfCreds = base64_encode($username . ':' . $password);
        $reqBody = $request->get_body_params();
        $code = null;
        if(isset($reqBody['code'])) {
            $code = esc_sql(trim($reqBody['code']));
        }
        else {
            $_SESSION['errors'] = [
                'code' => '{code} is a required parameter.'
            ];
            return wp_redirect(site_url(), 302);
        }
        $predicate = preg_match('/^[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}$/', $code);
        if($predicate === 0) {
            $_SESSION['errors'] = [
                'code' => 'Invalid code. Please try again with a correct one.'
            ];
            wp_redirect(site_url() . '?modal-state=1', 302);
            exit();
        }
        $res = wp_remote_get('https://etesting.space/wp-json/wc-pimwick/v1/pw-gift-cards?limit=1&number=' . $code, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $bsfCreds
            ]
        ]);
        $body = json_decode(wp_remote_retrieve_body($res), true);
        $_SESSION['card-active'] = intVal($body[0]['active']);
        $_SESSION['card-balance'] = floatVal($body[0]['balance']);
        $_SESSION['card-id'] = intVal($body[0]['pimwick_gift_card_id']);
        $_SESSION['card-number'] = $body[0]['number'];

        wp_redirect(site_url() . '?modal-state=1', 302);
        exit();
    }

    public static function update(WP_REST_Request $request) {
        $username = 'ck_ad713bc399f8d63da81a3583057b3e7b3d0899d4';
        $password = 'cs_ee0259074bde553ce2008e6e0cd3994f99da77d5';
        $bsfCreds = base64_encode($username . ':' . $password);
        $res = wp_remote_request('https://etesting.space/wp-json/wc-pimwick/v1/pw-gift-cards/1267', [
            'method' => 'PATCH',
            'body' => json_encode([
                "amount" => 500
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $bsfCreds
            ]
        ]);
        $body = wp_remote_retrieve_body($res);
        wp_send_json(json_decode($body, true));
    }

    public static function store() {

    }
}