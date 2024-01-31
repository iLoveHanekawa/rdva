jQuery(($: JQueryStatic) => {
    const nav = $('.navbar');
    const navlinks = $(nav).eq(0).find('a')
    navlinks.each((index, navlink) => {
        if(window.location.href === 'http://localhost/rdva/' || window.location.href === 'http://localhost/rdva') {
            $(navlink).removeClass();
            $(navlink).addClass('active-nav');
        }
    })
});
