function abrirJogo(jogo) {
    // Recupera o nickname e o ícone do usuário logado do localStorage
    const nickname = localStorage.getItem('nickname');
    const iconeUser = localStorage.getItem('icone_user'); // Supondo que o ícone do usuário também está armazenado

    // Faz a requisição para o Flask enviando o nome do jogo, nickname e ícone do usuário
    fetch('http://localhost:5000/abrir_jogo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            jogo: jogo, 
            nickname: nickname, 
            icone_user: iconeUser  // Envia o ícone do usuário
        }),
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
