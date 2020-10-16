require('./reporting.scss');
const Chart = require('chart.js');



// on check si l'élément existe
let vjsId = 'reportingApp';
let el = document.getElementById(vjsId);
let oVjs = {};
if(el) {

  // Vue init
  oVjs = new Vue({
    delimiters: ['${', '}'],
    el: '#reportingApp',
    data: {
      activeReport: {},
      reportData: {},
      reportOptions: {},
      chart: null,
      table: '',
      dateX: 'Q',
    },
    mounted: function () {
      // ge trad
      this.trad = JSON.parse(this.$el.getAttribute('data-tradjs'));
      this.elVisuCtx = document.getElementById('reportingAppVisuGraph').getContext('2d');
    },
    methods: {
      selectReport (e) {
        this.activeReport = {
          type: e.target.getAttribute('data-type'),
          name: e.target.getAttribute('data-menu')
        }
      },
      refreshData () {
        this.$http.get('/api/reporting/'+this.activeReport.name, {'params': {type: this.activeReport.type, dateX: this.dateX}}).then(
          res => {
            this.reportData = res.data.data;
            this.reportOptions = res.data.options;
            this.drawReport();
          }, res => {
            console.log('error');
          }
        )
      },
      drawReport () {
        // on détruit le graph courant
        this.table = '';
        if(this.chart != null){
          this.chart.destroy();
        }
        // todo trad serveur
        this.reportOptions.title.text = this.trad[this.reportOptions.title.text];
        this.reportData.datasets[0].label = this.trad[this.reportData.datasets[0].label];
        if(this.reportData.datasets.length > 1){
          this.reportData.datasets[1].label = this.trad[this.reportData.datasets[1].label];
          this.reportData.datasets[2].label = this.trad[this.reportData.datasets[2].label];
        }
        if(this.activeReport.type == 'table'){
          // tableau
          // todo click = opportunity.index filtré
          let totals = {};
          this.table = '<h3>' + this.reportOptions.title.text + '</h3>';
          this.table += '<table class="table table-striped"><tr><th>Période</th>';
          for(let dataset of this.reportData.datasets){
            this.table += '<th>' + dataset.label + '</th>';
            totals[dataset.label] = 0;
          }
          this.table += '</tr>';
          let i = 0;
          while(i < this.reportData.labels.length){
            this.table += '<tr>';
            this.table += '<td>' + this.reportData.labels[i] + '</td>';
            for(let dataset of this.reportData.datasets){
              this.table += '<td>' + (dataset.data[i] || '0') + '</td>';
              totals[dataset.label] += parseFloat(dataset.data[i]);
            }
            this.table += '</tr>';
            i++;
          }
          // total
          this.table += '<tr><td><strong>Total</strong></td>';
          for(let dataset of this.reportData.datasets){
            this.table += '<td><strong>' + (totals[dataset.label] || '0') + '</strong></td>';
          }
          this.table += '</tr>';
          this.table += '</table>';
        }else{

          // unit
          this.reportOptions.tooltips = {
            callbacks: {
              label: (item) => `${item.yLabel} ${this.reportOptions.unit}`,
            },
          }

          // graph
          this.chart = new Chart(this.elVisuCtx, {
            type: this.activeReport.type,
            data: this.reportData,
            options: this.reportOptions
          });
        }
      }
    },
    watch: {
      // when activeReport change, fetch data from server
      activeReport: function (val) {
        this.refreshData();
      },
    }
  });
}

module.exports = oVjs;
