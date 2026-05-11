import axios from 'axios';
window.axios = axios;

// Importar biblioteca para apresentar o alerta
import Swal from 'sweetalert2';
window.Swal = Swal;

// Importar biblioteca para gerar gráfico
import Chart from 'chart.js/auto';
// Deixar disponível globalmente, para usar no Blade
window.Chart = Chart;


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
