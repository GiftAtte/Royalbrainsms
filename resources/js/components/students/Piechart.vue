<script>
import { Pie } from "vue-chartjs";
export default {
    extends: Pie,
    props: ["numPresent", "numAbsent"],
    data() {
        return {
            chartData: {
                labels: ["PRESENTS", "ABSENT"],
                datasets: [
                    {
                        borderWidth: 1,
                        borderColor: ["green", "red"],
                        backgroundColor: ["green", "red"],
                        data: [this.numPresent, this.numAbsent],
                    },
                ],
            },
            options: {
                legend: {
                    display: true,
                },
                responsive: true,
                maintainAspectRatio: false,
            },
        };
    },
    mounted() {
        this.renderChart(this.chartData, this.options);
    },
};
</script>
