require('./show.scss');

// on check si l'élément existe
let vjsId = 'vjs-tender-show';
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
        console.log(link)
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
                      console.log('workedTime upload error:')
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
      showPopupEditWorkedDays (e, i) {
        let evjs = this;
        this.displayPopup(e.currentTarget, (jsonRes) => {
          evjs.tender.totalWorkedDays = jsonRes.totalWorkedDays;
          evjs.workedTimes[i] = jsonRes.workedTime;
          //workedTime = jsonRes.workedTime;
        })
      },
      // display add worked days form in popup
      showPopupAddWorkedDays (e) {
        let evjs = this;
        this.displayPopup(e.currentTarget, (jsonRes) => {
          evjs.tender.totalWorkedDays = jsonRes.totalWorkedDays;
          evjs.workedTimes.push(jsonRes.workedTime);
        })
      }
    }
  });
}

module.exports = oVjs;
