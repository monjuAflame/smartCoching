window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
    require('admin-lte');
    /*
    require( 'jszip' );
    require( 'pdfmake' );
    require( 'datatables.net-dt' )();
    require( 'datatables.net-buttons-dt' )();
    require( 'datatables.net-buttons/js/buttons.colVis.js' )();
    require( 'datatables.net-buttons/js/buttons.flash.js' )();
    require( 'datatables.net-buttons/js/buttons.html5.js' )();
    require( 'datatables.net-buttons/js/buttons.print.js' )();
    
    */
    window.JSZip = require( "jszip" );

    require('../../public/js/jquery.dataTables.min.js');
    require('../../public/js/dataTables.buttons.min.js');
    require('../../public/js/jszip.min.js');
    require('../../public/js/pdfmake.min.js');
    require('../../public/js/vfs_fonts.js'); 
    require('../../public/js/buttons.html5.min.js');
    //fonts
    require('typeface-nunito');
    

    // require('bootstrap-table');

   
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

const token = window.localStorage.getItem('access_token');

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    disableStats: true,
    auth:{
        headers:{
            Authorization: `Bearer ${token}`
        }
    }
});
