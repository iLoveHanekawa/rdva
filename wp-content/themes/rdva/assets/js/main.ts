jQuery(($: JQueryStatic) => {
    const nav: JQuery<HTMLElement> = $('.navbar');
    const navlinks: JQuery<HTMLAnchorElement> = $(nav).eq(0).find('a');
    const url: URL = new URL(window.location.href);
    const query: URLSearchParams = url.searchParams;
    if(query.has('modal-state') && parseInt(query.get('modal-state')) === 1) {
        $('.modal').addClass('modal-show');
        $('.modal').removeClass('modal-hide');
    }
    navlinks.each((index: number, navlink: HTMLAnchorElement) => {
        if((url.host + url.pathname) === 'localhost/rdva/' || (url.host + url.pathname) === 'localhost/rdva') {
            $(navlink).removeClass();
            $(navlink).addClass('active-nav');
        }
    })
    $(() => {
        const username: string = 'ck_ad713bc399f8d63da81a3583057b3e7b3d0899d4';
        const password: string = 'cs_ee0259074bde553ce2008e6e0cd3994f99da77d5';
        const bsfCreds: string = btoa(username + ':' + password)
        $.ajax({
            beforeSend: () => {
                $('.debug').find('pre').text('Loading...');
            },
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'basic ' + bsfCreds
            },
            url: 'https://etesting.space/wp-json/wc-pimwick/v1/pw-gift-cards?number=PGDE-RZJK-H8Z8-WM3J',
            success: (res) => {
                $('.debug').find('pre').text(JSON.stringify(res, null, 2))
            },
            error: (error) => {
                console.log(error);
            }
        });
    });

    $('.primary-button').on('click', () => {
        $('.modal').addClass('modal-show');
        $('.modal').removeClass('modal-hide');
    });
    $('.cross').on('click', () => {
        $('.modal').addClass('modal-hide');
        $('.modal').removeClass('modal-show');
    });
});
