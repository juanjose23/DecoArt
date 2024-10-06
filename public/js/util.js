document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            document.title = "¡Vuelve pronto!";  // Cambia el título cuando la pestaña no está visible
        } else {
            document.title = "Deco Arts";  // Cambia el título cuando la pestaña vuelve a ser visible
        }
    });
});



const marquee = document.getElementById('marquee');

let isDown = false;
let startX;
let scrollLeft;

marquee.addEventListener('mousedown', (e) => {
    isDown = true;
    marquee.classList.add('active'); // Puedes agregar una clase para estilos opcionales
    startX = e.pageX - marquee.offsetLeft;
    scrollLeft = marquee.scrollLeft;
});

marquee.addEventListener('mouseleave', () => {
    isDown = false;
    marquee.classList.remove('active');
});

marquee.addEventListener('mouseup', () => {
    isDown = false;
    marquee.classList.remove('active');
});

marquee.addEventListener('mousemove', (e) => {
    if (!isDown) return; // Si no se está haciendo clic, no hace nada
    e.preventDefault(); // Prevenir el comportamiento de selección de texto
    const x = e.pageX - marquee.offsetLeft;
    const walk = (x - startX) * 2; // Multiplica por 2 para ajustar la velocidad
    marquee.scrollLeft = scrollLeft - walk;
});

// Opcional: Para que el marquee se reinicie al final
marquee.addEventListener('scroll', () => {
    if (marquee.scrollLeft + marquee.clientWidth >= marquee.scrollWidth) {
        marquee.scrollLeft = 0;
    }
});

var swiper = new Swiper(".default-carousel", {
    loop: true,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});


document.querySelectorAll('.accordion-toggle').forEach(button => {
    button.addEventListener('click', () => {
        // Cerrar otros acordeones
        document.querySelectorAll('.accordion-content').forEach(content => {
            if (content !== button.nextElementSibling) {
                content.style.maxHeight = null;
            }
        });

        // Alternar el acordeón actual
        const content = button.nextElementSibling;
        if (content.style.maxHeight) {
            content.style.maxHeight = null;
        } else {
            content.style.maxHeight = content.scrollHeight + 'px'; // Ajusta el max-height según el contenido
        }
    });
});
