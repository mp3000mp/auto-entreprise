require('./show.scss');

// on check si l'élément existe
let vjsId = 'vjs-opportunity-show';
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
      // display add file form in popup
      showPopupAddFile (e) {
        this.tenders[0].totalWorkedDays++;
        this.$refs.addForm.style.display = 'block';
        Swal.fire({
          title: this.trad.file.add,
          html: this.$refs.addForm,
          showCloseButton: true,
          showConfirmButton: false,
        })
        this.$refs.addForm.classList.remove('d-none');
        this.$refs.addForm.classList.add('d-block');
      },
      // submit add file form in ajax
      postFile (e) {
        let formData = new FormData(e.target);
        let vjs = this;
        this.$http.post(e.target.getAttribute("action"), formData, {headers: { 'Content-Type': 'multipart/form-data'}}).then(
          res => {
            this.files.push(res.data.file);
            Swal.close();
          }, res => {
            console.log('file upload error:')
            console.log(res)
          }
        )
      },
      // display add worked days form in popup
      showPopupAddWorkedDays (e, i) {
        let tender = this.tenders[i];
        //let vjs = this;
        this.$http.get(e.currentTarget.href).then(
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
                      if(res.data.status != '1'){
                        let newForm = $(res.data).find('form');
                        form.html(newForm.html())
                      }else{
                        tender.totalWorkedDays = res.data.totalWorkedDays;
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
      }
    }
  });
}

module.exports = oVjs;
