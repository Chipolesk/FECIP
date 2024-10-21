function abrirJogo(jogo) {
    fetch('http://127.0.0.1:5000/abrir_jogo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ jogo: jogo }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("O Launcher precisa estar aberto");
            console.error(data.error); // Exibe o erro retornado do servidor
           
        } else {
            console.log('Jogo aberto:', data.message);
        }
    })
    .catch((error) => {
        alert("O Launcher precisa estar aberto");
        console.error('Erro ao abrir o jogo:', error);
    });
}
// Função para buscar o número de jogadores de todos os jogos e atualizar o HTML
function atualizarTotalJogadores() {
    fetch('https://digitalcore.azurewebsites.net/backend/totalUsers.php')
        .then(response => response.json())
        .then(data => {
            console.log(data); // Exibe os dados no console para inspeção

            // Verifica se a resposta é um vetor
            if (Array.isArray(data)) {
                // Para cada jogo retornado, atualiza a label correspondente
                data.forEach(jogo => {
                    const className = jogo.nome_jogo.replace(/ /g, '').replace(/:/g, '').toLowerCase();
                    const totalPlayersLabel = document.querySelector(`.${className}`);  // Seleciona a label com a classe do jogo
                    if (totalPlayersLabel) {
                        totalPlayersLabel.innerText = jogo.acessos_jogo;  // Atualiza com o valor de acessos
                    } else {
                        console.error(`Label não encontrada para o jogo: ${jogo.nome_jogo}`);
                    }
                });
            } else {
                console.error('A resposta não é um array:', data);
            }
        })
        .catch(error => console.error('Erro ao buscar o total de jogadores:', error));
}

// Chama a função quando a página carregar
document.addEventListener('DOMContentLoaded', atualizarTotalJogadores);



// Adicionando os eventos de clique para os botões, verificando se eles existem
const botaoDigismash = document.getElementById('botao-digismash');
if (botaoDigismash) {
    botaoDigismash.addEventListener('click', function() {
        abrirJogo('DigiSmash');
    });
}

const botaoSto = document.getElementById('botao-sto');
if (botaoSto) {
    botaoSto.addEventListener('click', function() {
        abrirJogo('STO');
    });
}

const botaoHih = document.getElementById('botao-hih');
if (botaoHih) {
    botaoHih.addEventListener('click', function() {
        abrirJogo('HiH');
    });
}

const botaoDress = document.getElementById('botao-dress');
if (botaoDress) {
    botaoDress.addEventListener('click', function() {
        abrirJogo('DRESS');
    });
}

const botaoCupcake = document.getElementById('botao-cupcake');
if (botaoCupcake) {
    botaoCupcake.addEventListener('click', function() {
        abrirJogo('CUPCAKE');
    });
}

const botaoFlappy = document.getElementById('botao-flappy');
if (botaoFlappy) {
    botaoFlappy.addEventListener('click', function() {
        abrirJogo('FLAPPY');
    });
}

