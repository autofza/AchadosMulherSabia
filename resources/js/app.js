import './bootstrap';
import $ from 'jquery';
import 'summernote/dist/summernote-lite';
import 'summernote/dist/summernote-lite.css';

window.$ = window.jQuery = $;

// Inicializar Summernote
$(function () {
    const summernoteEl = $('#summernote');
    if (summernoteEl.length) {
        summernoteEl.summernote({
            height: 150,
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ JS carregado");

    /*** Sidebar ***/
    const sidebar = document.getElementById("sidebar");
    const sidebarHeader = document.getElementById("sidebarHeader");
    const navbarToggleBtn = document.getElementById("toggleSidebar");
    const sidebarToggleBtn = document.getElementById("sidebarToggleBtn");

    // Criar overlay dinamicamente se não existir
    let sidebarOverlay = document.getElementById("sidebarOverlay");
    if (!sidebarOverlay) {
        sidebarOverlay = document.createElement("div");
        sidebarOverlay.id = "sidebarOverlay";
        sidebarOverlay.classList.add("hidden");
        sidebarOverlay.style.cssText = "position: fixed; inset: 0; z-index: 40; background: rgba(0,0,0,0.5);";
        document.body.appendChild(sidebarOverlay);
    }

    function toggleSidebar() {
        sidebar.classList.toggle("collapsed");

        if (window.innerWidth <= 768) {
            if (sidebar.classList.contains("collapsed")) {
                sidebarOverlay.classList.add("hidden");
                document.body.classList.remove("sidebar-open");
            } else {
                sidebarOverlay.classList.remove("hidden");
                document.body.classList.add("sidebar-open");
            }
        }
    }

    if (navbarToggleBtn) {
        navbarToggleBtn.addEventListener("click", toggleSidebar);
    }
    if (sidebarHeader) {
        sidebarHeader.addEventListener("click", (e) => {
            if (!e.target.closest("#sidebarToggleBtn")) toggleSidebar();
        });
    }
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            toggleSidebar();
        });
    }
    sidebarOverlay.addEventListener("click", () => {
        sidebar.classList.add("collapsed");
        sidebarOverlay.classList.add("hidden");
        document.body.classList.remove("sidebar-open");
    });

    // Fechar sidebar clicando fora em mobile
    document.addEventListener("click", (e) => {
        if (
            window.innerWidth <= 768 &&
            sidebar &&
            !sidebar.contains(e.target) &&
            navbarToggleBtn &&
            !navbarToggleBtn.contains(e.target) &&
            !sidebarOverlay.contains(e.target) &&
            !sidebar.classList.contains("collapsed")
        ) {
            sidebar.classList.add("collapsed");
            sidebarOverlay.classList.add("hidden");
            document.body.classList.remove("sidebar-open");
        }
    });

    console.log("✅ Sidebar configurada com sucesso!");

    /*** Dropdown usuário ***/
    const dropdownButton = document.getElementById('userDropdownButton');
    const dropdownContent = document.getElementById('dropdownContent');

    if (dropdownButton && dropdownContent) {
        dropdownButton.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownContent.classList.toggle('hidden');
        });

        window.addEventListener('click', (event) => {
            if (!dropdownButton.contains(event.target) && !dropdownContent.contains(event.target)) {
                dropdownContent.classList.add('hidden');
            }
        });
    }

    /*** Dark Mode ***/
    const htmlEl = document.documentElement;
    const themeToggle = document.getElementById("themeToggle");
    const iconMoon = document.getElementById("iconMoon");
    const iconSun = document.getElementById("iconSun");

    function updateIcons() {
        if (!iconMoon || !iconSun) return;
        if (htmlEl.classList.contains("dark")) {
            iconMoon.classList.remove("hidden");
            iconSun.classList.add("hidden");
        } else {
            iconMoon.classList.add("hidden");
            iconSun.classList.remove("hidden");
        }
    }

    const isDarkMode = localStorage.theme === "dark" ||
        (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches);

    htmlEl.classList.toggle("dark", isDarkMode);
    updateIcons();

    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            htmlEl.classList.toggle("dark");
            localStorage.theme = htmlEl.classList.contains("dark") ? "dark" : "light";
            updateIcons();
        });
    }

    /*** Formatar campos de moeda R$ ***/
    $(document).on('input', '[data-type="currency"]', function () {
        let value = $(this).val().replace(/\D/g, "");
        value = (value / 100).toFixed(2) + "";
        value = value.replace(".", ",");
        value = "R$ " + value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        $(this).val(value);
    });
});

/*** SweetAlert2 - Confirmar exclusão ***/
window.confirmDelete = function (id) {
    Swal.fire({
        title: "Tem certeza?",
        text: "Essa ação não pode ser desfeita!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form-' + id);
            if (form) form.submit();
        }
    });
};

/*** SweetAlert2 - Confirmar Status do produto ***/
window.confirmstatus = function (id) {
    const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-all',
        cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-all ml-4',
        actions: 'flex justify-center gap-4' // 👈 Controla o espaçamento entre os botões
    },
    buttonsStyling: false
});

    swalWithBootstrapButtons.fire({
        title: "Tem certeza?",
        text: "Em alterar o status do produto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, alterar status!",
        cancelButtonText: "Cancelar",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // ✅ SUBMETE O FORMULÁRIO
            const form = document.getElementById(`toggle-${id}`);
            if (form) {
                form.submit();
            } else {
                Swal.fire('Erro', 'Formulário não encontrado!', 'error');
            }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Nenhuma alteração foi feita.",
                icon: "info",
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
};
