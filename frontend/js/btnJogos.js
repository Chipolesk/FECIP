function abrirJogo(jogo) {
    // Recupera o nickname do usuário logado do localStorage
    const nickname = localStorage.getItem('nickname');

    // Faz a requisição para o Flask enviando o nome do jogo e o nickname do usuário
    fetch('http://localhost:5000/abrir_jogo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ jogo: jogo, nickname: nickname }), // Envia o jogo e o nickname
    })
    .then(response => response.text())
    .then(data => {
        console.log('Jogo aberto:', data);
    })
    .catch((error) => {
        console.error('Erro ao abrir o jogo:', error);
    });
}

// Exemplo de como adicionar ao botão do DigiSmash
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
    abrirJogo('Dress O mama');
});
