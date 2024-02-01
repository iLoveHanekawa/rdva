<?php session_start() ?>
<?php get_header() ?>
<?php $cardRequestUrl = site_url() . '/wp-json/api/v1/card/get'; ?>
<div class='page'>
    <div class='heading'>
        <h1 class='headline'>Riyadvi assignment</h1>
    </div>
    <div class='rdva-button-container'>
        <button class="primary-button">Click me.</button>
    </div>
    <div class="modal">
        <form method="POST" action="<?= $cardRequestUrl ?>">
            <div class="form-top">
                <h2 class="rdva-name">Redeem gift card</h2>
                <button type="button" class='cross'>
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <?php wp_nonce_field('wp_rest'); ?>
            <label for="code" class='rdva-label'>Code</label>
            <?php 
                $value = '';
                if(isset($_SESSION['card-number'])) {
                    $value = esc_attr($_SESSION['card-number']);
                    unset($_SESSION['card-number']);
                }
            ?>
            <input id="code" name='code' class='rdva-input' value="<?= $value; ?>" type="text" required />
            <?php 
                if(isset($_SESSION['errors'])) {
                    $errors = $_SESSION['errors'];
                    unset($_SESSION['errors']);
                    if(isset($errors['code'])) { 
                        $error = $errors['code'] ?>
                        <div class="error"><?= $error ?></div>
                    <?php }
                }
            ?>
            <button class="secondary-button" type='submit'>Redeem</button>
            <?php if(isset($_SESSION['card-balance']) && isset($_SESSION['card-active']) && isset($_SESSION['card-id'])) { 
                $balance = $_SESSION['card-balance'];
                $active = $_SESSION['card-active'];
                $id = $_SESSION['card-id'];
                $showApplicationForm = $active === 1;
                unset($_SESSION['card-balance']);
                unset($_SESSION['card-active']);
                unset($_SESSION['card-id']);
            ?>
                <div class="balance-container">
                    <div class="rdva-name">
                        Balance: 
                        <span>
                            <?php echo '$' . esc_html($balance); ?>
                        </span>
                    </div>
                    <?php if($active === 1) { ?>
                        <span class="balance-valid"><?= 'The card is valid'; ?></span>
                    <?php } else { ?>
                        <span class="balance-invalid"><?= 'The card is invalid'; ?></span>
                    <?php } ?>
                </div>
            <?php } ?>
        </form>
        <form>
        <?php if($showApplicationForm) { ?>
            <label for="customer-number" class='rdva-label'>Customer code</label>
                <input id="customer-number" name='customer-number' class='rdva-input' type="number" required />
                <button class="tertiary-button" type='submit'>Apply payment</button>
            </form>
        <?php } ?>
    </div>
    <div class='debug'>
        <pre>Loading...</pre>
    </div>
</div>

<?php get_footer() ?>