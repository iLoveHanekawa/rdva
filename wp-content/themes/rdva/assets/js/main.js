jQuery(($) => {
    const nav = $('.navbar');
    const navlinks = $(nav).eq(0).find('a');
    navlinks.each((index, navlink) => {
        if (window.location.href === 'http://localhost/rdva/' || window.location.href === 'http://localhost/rdva') {
            $(navlink).removeClass();
            $(navlink).addClass('active-nav');
        }
    });
    $(() => {
        const username = 'ck_ad713bc399f8d63da81a3583057b3e7b3d0899d4';
        const password = 'cs_ee0259074bde553ce2008e6e0cd3994f99da77d5';
        const bsfCreds = btoa(username + ':' + password);
        $.ajax({
            beforeSend: () => {
                $('.debug').find('pre').text('Loading...');
            },
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'basic ' + bsfCreds
            },
            url: 'https://etesting.space/wp-json/wc-pimwick/v1/pw-gift-cards',
            success: (res) => {
                $('.debug').find('pre').text(JSON.stringify(res, null, 2));
            },
            error: (error) => {
                console.log(error);
            }
        });
    });
    $('.primary-button').on('click', () => {
        $('.modal').addClass('model-show');
        $('.modal').removeClass('model-hide');
    });
    $('.cross').on('click', () => {
        $('.modal').addClass('model-hide');
        $('.modal').removeClass('model-show');
    });
});
