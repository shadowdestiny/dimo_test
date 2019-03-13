<script>
import { VueCharts, VueChartJs } from "vue-chartjs";

export default {
  extends: VueCharts.Bar,
  mixins: [VueCharts.mixins.reactiveProp],
  data: function() {
    return {
      options: {
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: false,
                min: 0,
                max: 25,
                stepSize: 5
              },
              gridLines: {
                display: false
              }
            }
          ],
          xAxes: [
            {
              ticks: {
                beginAtZero: false
              },
              gridLines: {
                display: false
              }
            }
          ]
        },
        legend: {
          display: true,
          position: "right"
        },
        tooltips: {
          enabled: true,
          mode: "single",
          callbacks: {
            label: function(tooltipItems, data) {
              return "" + tooltipItems.yLabel;
            }
          }
        },
        responsive: true,
        maintainAspectRatio: false,
        height: 200
      }
    };
  },
  mounted() {
    // this.chartData is created in the mixin
    this.renderChart(this.chartData, this.options);
  }
};
</script>
