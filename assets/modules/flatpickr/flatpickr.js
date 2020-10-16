require('flatpickr');
require('./flatpickr.scss');

function MPFlatpickr() {


  // ---------------------------------------------------------------------------------------
  // ---------------------------------------------------------------------------------------
  //                DATETIME
  // ---------------------------------------------------------------------------------------

  // localization
  let locales = ['en', 'fr'];
  locales.forEach(function (locale) {
    // flatpickr
    if (locale != 'en') {
      require('flatpickr/dist/l10n/' + locale + '.js');
    }
  });


  this.flatpickrInit = function(element){

    fpDates = $(element.find('.flatpickrDate'));

    /**
     * Champs dates
     * on applique la config suivante + report des valeurs dans les champs form sur tous les .flatpickrTime
     */
    const fpd = flatpickr(fpDates, {
      locale: document.documentElement.lang, // locale
      allowInput: true,
      // compatible symfony form => on met à jour les champs cachés à la sélection d'une date
      onChange: function (selectedDates, dateStr, instance) {
        let d = selectedDates[0];
        let elName = instance.input.name;
        document.getElementById(elName + '_day').value = d.getDate();
        document.getElementById(elName + '_month').value = d.getMonth() + 1;
        document.getElementById(elName + '_year').value = d.getFullYear();
      }
    });

    fpDates.change(function(){
      let elName = $(this).attr('name');
      let s = $(this).val();
      if(s == ''){
        document.getElementById(elName + '_day').value = '';
        document.getElementById(elName + '_month').value = '';
        document.getElementById(elName + '_year').value = '';
      }else{
        if(/[12][90]\d{2}\-[01]\d\-[0123]\d/.test(s)){
          let d = new Date(s);
          document.getElementById(elName + '_day').value = d.getDate();
          document.getElementById(elName + '_month').value = d.getMonth() + 1;
          document.getElementById(elName + '_year').value = d.getFullYear();
        }
      }
    })

    let fpTimes = $(element.find('.flatpickrTime'));

    /**
     * Champs time
     * on applique la config suivante + report des valeurs dans les champs form sur tous les .flatpickrTime
     */
    const fpt = flatpickr(fpTimes, {
      locale: document.documentElement.lang, // locale
      enableTime: true,
      noCalendar: true,
      time_24hr: true,
      allowInput: false,
      // compatible symfony form => on met à jour les champs cachés à la sélection d'un time
      onChange: function (selectedTimes, timeStr, instance) {
        let d = selectedTimes[0];
        let elName = instance.input.name;
        document.getElementById(elName + '_time_hour').value = d.getHours();
        document.getElementById(elName + '_time_minute').value = d.getMinutes();
      }
    });

  }

  let forms = $('form');
  this.flatpickrInit(forms);

}

module.exports = new MPFlatpickr();
