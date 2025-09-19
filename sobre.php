<?php
$page_title = 'Sobre Nós - BEEFIT';
require_once 'includes/header.php';
?>

<div class="container about-page">
    <h1 class="section-title">Sobre a BEEFIT</h1>

    <div class="about-content">
        <div class="about-text">
            <h2>Nossa História</h2>
            <p>
                A BEEFIT nasceu da paixão por moda e da crença de que se vestir bem é uma forma de expressão.
                Nossa missão é oferecer peças de alta qualidade, com design moderno e sofisticado, que acompanham
                as últimas tendências e se adaptam ao seu estilo de vida.
            </p>
            <p>
                Cada coleção é cuidadosamente selecionada para garantir conforto, durabilidade e, claro, muito estilo.
                Valorizamos a exclusividade e a atenção aos detalhes, desde a escolha dos tecidos até o acabamento final.
            </p>
        </div>
        <div class="about-image">
            <!-- Imagem representativa da marca ou da loja -->
            <img src="https://via.placeholder.com/500x350/cccccc/ffffff?text=Nossa+Loja" alt="Imagem da loja BEEFIT">
        </div>
    </div>

    <section class="location-section">
        <h2 class="section-title">Nossa Localização</h2>
        <div class="location-content">
            <div class="address">
                <h3>Venha nos visitar!</h3>
                <p><strong>Endereço:</strong> Rua da Moda, 123, Bairro Estilo, Cidade Fashion - SP</p>
                <p><strong>Horário de Funcionamento:</strong></p>
                <p>Segunda a Sexta: 09:00 - 18:00</p>
                <p>Sábado: 09:00 - 14:00</p>
            </div>
            <div class="map">
                <!-- O código de incorporação do Google Maps vai aqui -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3657.197545229074!2d-46.65657188448813!3d-23.56133936744955!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce59c8da0aa315%3A0x202115e9b95590a7!2sMASP%20-%20Museu%20de%20Arte%20de%20S%C3%A3o%20Paulo%20Assis%20Chateaubriand!5e0!3m2!1spt-BR!2sbr!4v1663888344872!5m2!1spt-BR!2sbr"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>
</div>

<?php
require_once 'includes/footer.php';
?>
