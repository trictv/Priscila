// Executa o script quando o DOM estiver totalmente carregado
document.addEventListener('DOMContentLoaded', function() {

    // --- Galeria de Imagens da Página de Detalhes do Produto ---
    const mainProductImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail');

    if (mainProductImage && thumbnails.length > 0) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Pega o caminho da imagem do thumbnail clicado
                const newImageSrc = this.src;

                // Atualiza a imagem principal
                mainProductImage.src = newImageSrc;

                // Atualiza a classe 'active' para o thumbnail
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Define o primeiro thumbnail como ativo inicialmente
        if (thumbnails.length > 0) {
            thumbnails[0].classList.add('active');
        }
    }

    // --- Carrossel de Banners da Home Page ---
    const carousel = document.querySelector('.banner-carousel');
    if (carousel) {
        const slides = document.querySelectorAll('.banner-slide');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let currentIndex = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
        }

        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
                showSlide(currentIndex);
            });

            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
                showSlide(currentIndex);
            });
        }

        // Auto-play (opcional)
        // setInterval(() => {
        //     nextBtn.click();
        // }, 5000);
    }
});
