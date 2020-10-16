
// on check si l'élément existe
let vjsId = 'loginForm';
let el = document.getElementById(vjsId);
let oVjs = {};
if(el) {

  // vue init
   login = new Vue({
    delimiters: ['${', '}'],
    el: '#loginForm',
    data: {
      emailNeeded: false,
      email: document.getElementById('username').dataset['value'],
    },
    methods: {
      test (e) {
        if(this.email == ''){
          this.emailNeeded = true;
          e.preventDefault();
        }
      }
    },
    watch: {
      email (val) {
        if (this.emailNeeded && val != '') {
          this.emailNeeded = false;
        }
      }
    }
  });
}

module.exports = oVjs;
