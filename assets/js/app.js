// css
require('../css/app.scss');

//
require('bootstrap');


global.axios = require('axios')
global.Swal = require('sweetalert2')

global.mpFlatpickr = require('../modules/flatpickr/flatpickr')

global.Vue = require('vue').default
Vue.prototype.$http = axios;

// vuejs instances indépendantes
require('../modules/login/login')
require('../modules/reporting/reporting')
require('../modules/opportunity/show')
require('../modules/tender/show')
require('../modules/cost/index')

