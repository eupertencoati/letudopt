<?php
$pageTitle = "Política de Privacidade";
require_once 'config.php';
include 'includes/header.php';
?>

<main class="legal-page">
    <div class="container">
        <h1>Política de Privacidade</h1>
        <p class="last-update">Última atualização: <?= date('d/m/Y') ?></p>
        
        <div class="legal-content">
            <section>
                <h2>1. Introdução</h2>
                <p>A letudo.pt está empenhada em proteger a sua privacidade. Esta Política de Privacidade explica como recolhemos, usamos e protegemos os seus dados pessoais.</p>
            </section>

            <section>
                <h2>2. Dados que Recolhemos</h2>
                <p>Podemos recolher os seguintes tipos de dados:</p>
                <ul>
                    <li><strong>Dados de identificação:</strong> Nome, email, morada, telefone</li>
                    <li><strong>Dados de encomenda:</strong> Histórico de compras, produtos preferidos</li>
                    <li><strong>Dados de navegação:</strong> IP, tipo de browser, páginas visitadas</li>
                    <li><strong>Dados de pagamento:</strong> Informações necessárias para processar pagamentos</li>
                </ul>
            </section>

            <section>
                <h2>3. Como Usamos os Seus Dados</h2>
                <p>Utilizamos os seus dados para:</p>
                <ul>
                    <li>Processar e gerir as suas encomendas</li>
                    <li>Comunicar consigo sobre a sua conta e encomendas</li>
                    <li>Melhorar os nossos serviços e experiência do utilizador</li>
                    <li>Enviar comunicações de marketing (apenas com o seu consentimento)</li>
                    <li>Cumprir obrigações legais</li>
                </ul>
            </section>

            <section>
                <h2>4. Base Legal para o Tratamento</h2>
                <p>Tratamos os seus dados com base em:</p>
                <ul>
                    <li><strong>Execução de contrato:</strong> Para processar as suas encomendas</li>
                    <li><strong>Consentimento:</strong> Para newsletters e marketing</li>
                    <li><strong>Obrigação legal:</strong> Para faturação e contabilidade</li>
                    <li><strong>Interesse legítimo:</strong> Para melhorar os nossos serviços</li>
                </ul>
            </section>

            <section>
                <h2>5. Partilha de Dados</h2>
                <p>Os seus dados podem ser partilhados com:</p>
                <ul>
                    <li>Transportadoras para entrega das encomendas</li>
                    <li>Processadores de pagamento</li>
                    <li>Prestadores de serviços (hosting, email marketing)</li>
                    <li>Autoridades competentes quando exigido por lei</li>
                </ul>
                <p><strong>Nunca vendemos os seus dados a terceiros.</strong></p>
            </section>

            <section>
                <h2>6. Conservação de Dados</h2>
                <p>Conservamos os seus dados pelo período necessário para:</p>
                <ul>
                    <li>Cumprir as finalidades para que foram recolhidos</li>
                    <li>Cumprir obrigações legais (faturação: 10 anos)</li>
                    <li>Resolver litígios e fazer valer os nossos direitos</li>
                </ul>
            </section>

            <section>
                <h2>7. Os Seus Direitos</h2>
                <p>De acordo com o RGPD, tem os seguintes direitos:</p>
                <ul>
                    <li><strong>Acesso:</strong> Solicitar uma cópia dos seus dados</li>
                    <li><strong>Retificação:</strong> Corrigir dados inexatos</li>
                    <li><strong>Eliminação:</strong> Solicitar a eliminação dos seus dados</li>
                    <li><strong>Oposição:</strong> Opor-se ao tratamento dos seus dados</li>
                    <li><strong>Portabilidade:</strong> Receber os seus dados num formato estruturado</li>
                    <li><strong>Limitação:</strong> Solicitar a limitação do tratamento</li>
                </ul>
                <p>Para exercer estes direitos, contacte-nos para: privacidade@letudo.pt</p>
            </section>

            <section>
                <h2>8. Cookies</h2>
                <p>O nosso site utiliza cookies para melhorar a sua experiência. Pode gerir as suas preferências de cookies através do nosso banner de cookies.</p>
            </section>

            <section>
                <h2>9. Segurança</h2>
                <p>Implementamos medidas de segurança técnicas e organizativas para proteger os seus dados contra acesso não autorizado, alteração ou destruição.</p>
            </section>

            <section>
                <h2>10. Menores</h2>
                <p>O nosso site destina-se a maiores de 18 anos. Não recolhemos intencionalmente dados de menores.</p>
            </section>

            <section>
                <h2>11. Transferências Internacionais</h2>
                <p>Os seus dados são processados em Portugal. Se houver transferências para fora da UE, garantimos proteções adequadas.</p>
            </section>

            <section>
                <h2>12. Alterações à Política</h2>
                <p>Podemos atualizar esta Política de Privacidade periodicamente. As alterações serão publicadas nesta página.</p>
            </section>

            <section>
                <h2>13. Contacto</h2>
                <p>Para questões sobre esta Política de Privacidade:</p>
                <ul>
                    <li>Email: privacidade@letudo.pt</li>
                    <li>Telefone: +351 123 456 789</li>
                    <li>Morada: Lisboa, Portugal</li>
                </ul>
                <p>Tem também o direito de apresentar reclamação à Comissão Nacional de Proteção de Dados (CNPD).</p>
            </section>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>