<?php
require_once dirname(__DIR__) . '\models\Transactions.php';
class GiftCardController {
    private static $username = 'ck_ad713bc399f8d63da81a3583057b3e7b3d0899d4';
    private static $password = 'cs_ee0259074bde553ce2008e6e0cd3994f99da77d5';
    public static function show(WP_REST_Request $request) {
        if(!session_id()) {
            session_start();
        }
        $bsfCreds = base64_encode(GiftCardController::$username . ':' . GiftCardController::$password);
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
        $bsfCreds = base64_encode(GiftCardController::$username . ':' . GiftCardController::$password);
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

    public static function store(WP_REST_Request $request) {
        if(!session_id()) {
            session_start();
        }
        $bsfCreds = base64_encode(GiftCardController::$username . ':' . GiftCardController::$password);
        $reqBody = $request->get_body_params();
        error_log(json_encode($_SESSION));
        error_log($reqBody['customer-number']);
        if(!isset($reqBody['customer-number'])) {
            $_SESSION['errors'] = [
                'customer-number' => 'customer-number is a required field.'
            ];
            error_log('number not set');
            wp_redirect(site_url() . '?modal-state=1', 302);
            exit();
        }
        else if(!isset($_SESSION['card-number'])) {
            $_SESSION['errors'] = [
                'card-number' => 'invalid transaction flow.'
            ];
            error_log('card number not set');
            wp_redirect(site_url() . '?modal-state=1', 302);
            exit();
        }
        $customerNumber = $reqBody['customer-number'];
        $cardNumber = $_SESSION['card-number'];
        $predicate = preg_match('/^[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}$/', $cardNumber);
        if($predicate === 0) {
            $_SESSION['errors'] = [
                'card-number' => 'Invalid card number. Please try again with a correct one.'
            ];
            error_log('invalid card number');
            wp_redirect(site_url() . '?modal-state=1', 302);
            exit();
        }
        $req = wp_remote_get('https://etesting.space/wp-json/wc-pimwick/v1/pw-gift-cards?limit=1&number=' . $cardNumber, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $bsfCreds
            ]
        ]);
        $body = json_decode(wp_remote_retrieve_body($req), true);
        $card = $body[0];
        if(!isset($card['balance'])) {
            $_SESSION['errors'] = [
                'internal-server-error' => 'sorry something wen\'t wrong.'
            ];
            error_log('invalid balance');
            wp_redirect(site_url() . '?modal-state=1', 302);
            exit();
        }
        error_log(json_encode($body));
        $isCustomerNumberValid = preg_match('/^\d{10}$/', $customerNumber);
        if($isCustomerNumberValid === 0) {
            $_SESSION['errors'] = [
                'customer-number' => 'Invalid phone number. Please try again with a correct one.'
            ];
            $_SESSION['card-active'] = intVal($card['active']);
            $_SESSION['card-balance'] = floatVal($card['balance']);
            $_SESSION['card-id'] = intVal($card['pimwick_gift_card_id']);
            $_SESSION['card-number'] = $card['number'];
            error_log('invalid phone number');
            wp_redirect(site_url() . '?modal-state=1', 302);
            exit();
        }

        // usually there would be a product or card here with amount
        $amount = -300;
        $balance = $card['balance'];
        if($amount < 0 && $balance < abs($amount)) {
            $_SESSION['errors'] = [
                'code' => 'Insufficient balance.'
            ];
            wp_redirect(site_url() . '?modal-state=1', 302);
            exit();
        }
        unset($_SESSION['card-number']);
        Transaction::create($customerNumber, $cardNumber, $balance);
        $pwres = wp_remote_request('https://etesting.space/wp-json/wc-pimwick/v1/pw-gift-cards/' . $card['pimwick_gift_card_id'], [
            'method' => 'PATCH',
            'body' => json_encode([
                "amount" => $amount
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $bsfCreds
            ]
        ]);
        $pwBody = wp_remote_retrieve_body($pwres);
        wp_send_json(json_decode($pwBody), true);
    }
}