import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';

// IMPORTAR O SUMMERNOTE PRIMEIRO!
//import 'summernote/dist/summernote-lite.js';   // JS oficial
//import 'summernote/dist/summernote-lite.css';  // CSS oficial DO SUMMERNOTE

// DEPOIS, IMPORTAR O TAILWIND E SEUS ESTILOS CUSTOMIZADOS
//import '../css/app_site.css';                  // Tailwind + customizações

window.$ = window.jQuery = $;
window.Alpine = Alpine;

//document.addEventListener('DOMContentLoaded', () => {
  //  if ($('#summernote').length) {
   //     $('#summernote').summernote({
            placeholder: 'Digite o conteúdo do blog...',
   //         tabsize: 2,
   //         height: 200,
   //     });
   // }
//});

Alpine.data('carousel', () => ({
    scrollAmount: 250,
    autoSlide: null,
    currentIndex: 0,
    total: 0,

    init() {
        this.total = this.$refs.carousel.children.length;
    },

    scroll(direction) {
        const carousel = this.$refs.carousel;
        carousel.scrollBy({ left: direction * this.scrollAmount, behavior: 'smooth' });

        // Atualiza índice aproximado
        this.currentIndex = Math.max(
            0,
            Math.min(this.currentIndex + direction, this.total - 1)
        );
    },

    goTo(index) {
        const carousel = this.$refs.carousel;
        carousel.scrollTo({ left: index * this.scrollAmount, behavior: 'smooth' });
        this.currentIndex = index;
    },

    startAutoSlide() {
        this.autoSlide = setInterval(() => {
            if (this.currentIndex >= this.total - 1) {
                this.goTo(0);
            } else {
                this.scroll(1);
            }
        }, 3000);
    },

    stopAutoSlide() {
        clearInterval(this.autoSlide);
    }
}));

document.addEventListener("click", function(e) {
    if (e.target && e.target.id === "loadMoreBtn") {
        let btn = e.target;
        let page = btn.dataset.page;
        let perPage = btn.dataset.perpage;
        let today = btn.dataset.today;

        fetch(`?page=${page}&perPage=${perPage}&today=${today}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.text())
        .then(html => {
            document.querySelector("#product-list").insertAdjacentHTML("beforeend", html);
            btn.dataset.page = parseInt(page) + 1;

            // remove botão se não tiver mais produtos
            if (!html.trim()) {
                btn.remove();
            }
        })
        .catch(err => console.error(err));
    }
});

Alpine.start();
