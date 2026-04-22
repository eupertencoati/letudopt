// Cookie Banner Management
document.addEventListener('DOMContentLoaded', function() {
    const cookieBanner = document.getElementById('cookie-banner');
    const cookieModal = document.getElementById('cookie-modal');
    const cookieAcceptBtn = document.getElementById('cookie-accept');
    const cookieSettingsBtn = document.getElementById('cookie-settings');
    const cookieModalClose = document.getElementById('cookie-modal-close');
    const cookieSaveBtn = document.getElementById('cookie-save');
    const cookieAnalytics = document.getElementById('cookie-analytics');
    const cookieMarketing = document.getElementById('cookie-marketing');

    // Verificar se o utilizador já aceitou os cookies
    if (!localStorage.getItem('cookiesAccepted')) {
        // Mostrar banner após 2 segundos
        setTimeout(() => {
            cookieBanner.classList.add('show');
        }, 2000);
    }

    // Aceitar todos os cookies
    if (cookieAcceptBtn) {
        cookieAcceptBtn.addEventListener('click', function() {
            acceptAllCookies();
        });
    }

    // Abrir definições de cookies
    if (cookieSettingsBtn) {
        cookieSettingsBtn.addEventListener('click', function() {
            cookieBanner.classList.remove('show');
            cookieModal.classList.add('show');
        });
    }

    // Fechar modal
    if (cookieModalClose) {
        cookieModalClose.addEventListener('click', function() {
            cookieModal.classList.remove('show');
            cookieBanner.classList.add('show');
        });
    }

    // Fechar modal ao clicar fora
    cookieModal.addEventListener('click', function(e) {
        if (e.target === cookieModal) {
            cookieModal.classList.remove('show');
            cookieBanner.classList.add('show');
        }
    });

    // Guardar preferências
    if (cookieSaveBtn) {
        cookieSaveBtn.addEventListener('click', function() {
            saveCookiePreferences();
        });
    }

    // Função para aceitar todos os cookies
    function acceptAllCookies() {
        localStorage.setItem('cookiesAccepted', 'true');
        localStorage.setItem('cookieAnalytics', 'true');
        localStorage.setItem('cookieMarketing', 'true');
        
        cookieBanner.classList.remove('show');
        
        // Inicializar analytics e marketing (aqui podes adicionar o teu código)
        initializeAnalytics();
        initializeMarketing();
        
        console.log('Todos os cookies aceites');
    }

    // Função para guardar preferências personalizadas
    function saveCookiePreferences() {
        localStorage.setItem('cookiesAccepted', 'true');
        localStorage.setItem('cookieAnalytics', cookieAnalytics.checked ? 'true' : 'false');
        localStorage.setItem('cookieMarketing', cookieMarketing.checked ? 'true' : 'false');
        
        cookieModal.classList.remove('show');
        
        // Inicializar apenas os cookies selecionados
        if (cookieAnalytics.checked) {
            initializeAnalytics();
        }
        if (cookieMarketing.checked) {
            initializeMarketing();
        }
        
        console.log('Preferências de cookies guardadas');
        console.log('Analytics:', cookieAnalytics.checked);
        console.log('Marketing:', cookieMarketing.checked);
    }

    // Função para inicializar analytics
    function initializeAnalytics() {
        // Aqui podes adicionar o código do Google Analytics ou outro
        console.log('Analytics initialized');
        
        // Exemplo: Google Analytics
        // if (typeof gtag !== 'undefined') {
        //     gtag('consent', 'update', {
        //         'analytics_storage': 'granted'
        //     });
        // }
    }

    // Função para inicializar marketing
    function initializeMarketing() {
        // Aqui podes adicionar pixels de Facebook, Google Ads, etc.
        console.log('Marketing initialized');
        
        // Exemplo: Facebook Pixel
        // if (typeof fbq !== 'undefined') {
        //     fbq('consent', 'grant');
        // }
    }

    // Função para verificar preferências de cookies
    function checkCookiePreferences() {
        return {
            accepted: localStorage.getItem('cookiesAccepted') === 'true',
            analytics: localStorage.getItem('cookieAnalytics') === 'true',
            marketing: localStorage.getItem('cookieMarketing') === 'true'
        };
    }

    // Disponibilizar função globalmente
    window.checkCookiePreferences = checkCookiePreferences;
});