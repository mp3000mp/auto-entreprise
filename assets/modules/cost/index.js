require('./index.scss');

// on check si l'élément existe
let vjsId = 'vjs-cost-show';
let el = document.getElementById(vjsId);
let oVjs = {};
if(el) {

  // get data
  let vjsData = JSON.parse(el.getAttribute('data-vjs'));

  // vue init
  oVjs = new Vue({
    delimiters: ['${', '}'],
    el: '#' + vjsId,
    data: vjsData,
    methods: {
      // display popup with form, launch callback when submit success
      displayPopup(link, cb){
        this.$http.get(link.href).then(
          res => {
            Swal.fire({
              html: res.data,
              showCloseButton: true,
              showConfirmButton: false,
              onOpen: (element) => {
                let form = $(element).find('form');
                mpFlatpickr.flatpickrInit(form);
                form.submit(() => {
                  let formData = new FormData(form.get(0));
                  this.$http.post(form.attr("action"), formData).then(
                    res => {
                      // si pas de json avec status = 1 => erreur
                      if(res.data.status != '1'){
                        let newForm = $(res.data).find('form');
                        form.html(newForm.html())
                      }else{
                        cb(res.data);
                        Swal.close();
                      }
                    }, res => {
                      console.log('cost upload error:')
                      console.log(res)
                    })
                  return false;
                })
              }
            })
          }, res => {
            console.log('form load error:')
            console.log(res)
          }
        )
      },
      // display add worked days form in popup
      showPopupEditCost (e, i) {
        let evjs = this;
        this.displayPopup(e.currentTarget, (jsonRes) => {
          evjs.costs[i] = jsonRes.cost;
          evjs.$forceUpdate();
        })
      },
      // display add worked days form in popup
      showPopupAddCost(e) {
        let evjs = this;
        this.displayPopup(e.currentTarget, (jsonRes) => {
          evjs.costs.push(jsonRes.cost);
        })
      }
    }
  });
}

module.exports = oVjs;
