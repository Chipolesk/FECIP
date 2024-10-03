function abrirJogo(jogo) {
    fetch('http://localhost:5000/abrir_jogo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ jogo: jogo }),
    })
    .then(response => response.text())
    .then(data => {
        console.log('Jogo aberto:', data);
    })
    .catch((error) => {
        console.error('Erro ao abrir o jogo:', error);
    });
}

// Exemplo de como adicionar ao botão do Digismash
document.getElementById('botao-digismash').addEventListener('click', function() {
    abrirJogo('DigiSmash');
});
