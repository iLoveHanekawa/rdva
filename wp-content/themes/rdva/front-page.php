<?php get_header() ?>
<div class='page'>
    <div class='heading'>
            <h1 class='headline'>Riyadvi assignment</h1>
    </div>
    <div class='rdva-button-container'>
        <button class="primary-button">Click me.</button>
    </div>
    <div class="modal">
        <form method="POST" action="#">
            <div class="form-top">
                <h2 class="rdva-name">Redeem gift card</h2>
                <button type="button" class='cross'>
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <label class='rdva-label'>Code</label>
            <input class='rdva-input' type="number" />
            <button class="secondary-button" type='submit'>Redeem</button>
        </form>
    </div>
    <div class='debug'>
        <pre>Loading...</pre>
    </div>
</div>

<?php get_footer() ?>