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
            console.error(data.error); // Exibe o erro retornado do servidor
            alert(data.error, "Launcher precisa estar aberto");
        } else {
            console.log('Jogo aberto:', data.message);
        }
    })
    .catch((error) => {
        console.error('Erro ao abrir o jogo:', error);
    });
}
// Função para buscar o número de jogadores de todos os jogos e atualizar o HTML
function atualizarTotalJogadores() {
    fetch('https://digitalcore.azurewebsites.net/backend/totalUsers.php')
        .then(response => response.json())  // Parse da resposta como JSON
        .then(data => {
            console.log(data); // Exibe os dados recebidos no console

            // Para cada jogo retornado, atualiza a label correspondente
            data.forEach(jogo => {
                // Constrói a classe com base no nome do jogo, removendo espaços e transformando em minusculas
                const className = jogo.nome_jogo.replace(/ /g, '').replace(/:/g, '').replace(/-/g, '').toLowerCase(); 
                const totalPlayersLabel = document.querySelector(`.${className}`);  // Seleciona a label com a classe do jogo
                if (totalPlayersLabel) {
                    totalPlayersLabel.innerText = jogo.acessos_jogo;  // Atualiza com o valor de acessos
                } else {
                    console.error(`Label não encontrada para o jogo: ${jogo.nome_jogo}`);
                }
            });
        })
        .catch(error => console.error('Erro ao buscar o total de jogadores:', error));
}
// Chama a função quando a página carregar
document.addEventListener('DOMContentLoaded', atualizarTotalJogadores);


// Adicionando os eventos de clique para os botões
document.getElementById('botao-digismash').addEventListener('click', function() {
    abrirJogo('DigiSmash');
});

document.getElementById('botao-sto').addEventListener('click', function() {
    abrirJogo('STO');
});

document.getElementById('botao-hih').addEventListener('click', function() {
    abrirJogo('HiH');
});

document.getElementById('botao-dress').addEventListener('click', function() {
    abrirJogo('DRESS');
});

