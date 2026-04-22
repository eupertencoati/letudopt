<?php
$pageTitle = "Termos e Condições";
require_once 'config.php';
include 'includes/header.php';
?>

<main class="legal-page">
    <div class="container">
        <h1>Termos e Condições</h1>
        <p class="last-update">Última atualização: <?= date('d/m/Y') ?></p>
        
        <div class="legal-content">
            <section>
                <h2>1. Informações Gerais</h2>
                <p>Bem-vindo à letudo.pt. Ao utilizar o nosso website, concorda com os presentes Termos e Condições. Se não concordar com algum dos termos, não deverá utilizar o nosso site.</p>
            </section>

            <section>
                <h2>2. Condições de Utilização</h2>
                <p>O conteúdo deste website é apenas para informação geral e está sujeito a alterações sem aviso prévio.</p>
                <ul>
                    <li>É proibida a reprodução total ou parcial do conteúdo sem autorização prévia</li>
                    <li>Os preços apresentados incluem IVA à taxa legal em vigor</li>
                    <li>Reservamo-nos o direito de limitar a quantidade de produtos disponíveis</li>
                </ul>
            </section>

            <section>
                <h2>3. Encomendas e Pagamentos</h2>
                <p>Ao efetuar uma encomenda no nosso site:</p>
                <ul>
                    <li>Confirma que tem mais de 18 anos</li>
                    <li>Os preços são apresentados em Euros (€)</li>
                    <li>O pagamento deve ser efetuado no ato da encomenda</li>
                    <li>Receberá um email de confirmação da sua encomenda</li>
                    <li>Reservamo-nos o direito de recusar uma encomenda por qualquer motivo</li>
                </ul>
            </section>

            <section>
                <h2>4. Envios e Entregas</h2>
                <p>Os prazos de entrega são estimados e começam a contar a partir da confirmação do pagamento.</p>
                <ul>
                    <li>Portugal Continental: 2-5 dias úteis</li>
                    <li>Ilhas: 5-10 dias úteis</li>
                    <li>Os custos de envio serão calculados no checkout</li>
                </ul>
            </section>

            <section>
                <h2>5. Direito de Devolução</h2>
                <p>Tem o direito de devolver os produtos no prazo de 14 dias a contar da data de receção, desde que:</p>
                <ul>
                    <li>O produto esteja em perfeito estado</li>
                    <li>Esteja na embalagem original</li>
                    <li>Não tenha sido utilizado</li>
                </ul>
            </section>

            <section>
                <h2>6. Responsabilidade</h2>
                <p>A letudo.pt não se responsabiliza por:</p>
                <ul>
                    <li>Erros ou omissões no conteúdo do site</li>
                    <li>Interrupções temporárias do serviço</li>
                    <li>Danos resultantes do uso do website</li>
                </ul>
            </section>

            <section>
                <h2>7. Alterações aos Termos</h2>
                <p>Reservamo-nos o direito de modificar estes Termos e Condições a qualquer momento. As alterações entram em vigor imediatamente após a sua publicação no site.</p>
            </section>

            <section>
                <h2>8. Contacto</h2>
                <p>Para qualquer questão sobre estes Termos e Condições, contacte-nos:</p>
                <ul>
                    <li>Email: info@letudo.pt</li>
                    <li>Telefone: +351 123 456 789</li>
                    <li>Morada: Lisboa, Portugal</li>
                </ul>
            </section>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>