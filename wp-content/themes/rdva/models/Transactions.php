<?php 

class Transaction {
    /** 
        * @var string 
    */
    private $customerNumber;
    /** 
        * @var string 
    */
    private $cardNumber;
    /** 
        * @var float 
    */
    private $balance;
    private function __construct(string $customerNumber, string $cardNumber, float $balance) {
        $this->cardNumber = $cardNumber;
        $this->customerNumber = $customerNumber;
        $this->balance = $balance;
    }
    private function getCardNumber() {
        return $this->cardNumber;
    }
    private function getCustomerNumber() {
        return $this->customerNumber;
    }
    private function getBalance() {
        return $this->balance;
    }
    public static function create(string $customerNumber, string $cardNumber, float $balance) {
        $newTransaction = new Transaction($customerNumber, $cardNumber, $balance);
        $userId = get_current_user_id();
        $postId = wp_insert_post([
            'post_title'    => 'Gift card transaction',
            'post_status'   => 'publish',
            'post_type'     => 'transactions',
            'post_author'   => $userId,
        ]);
        add_post_meta( $postId, 'customer_number', $newTransaction->getCustomerNumber());
        add_post_meta( $postId, 'card_number', $newTransaction->getCardNumber());
        add_post_meta( $postId, 'balance', $newTransaction->getBalance());
        return $newTransaction;
    }
}
