function abrirJogo(jogo) {

    fetch('http://localhost:5000/abrir_jogo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ jogo: jogo}),
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error(data.error); // Exibe o erro retornado do servidor
        } else {
            console.log('Jogo aberto:', data.message);
           
        }
    })
    .catch((error) => {
        console.error('Erro ao abrir o jogo:', error);
    });
}


document.getElementById('botao-digismash').addEventListener('click', function() {
    abrirJogo('DigiSmash');
document.getElementById('botao-sto').addEventListener('click', function() {
    abrirJogo('STO');
document.getElementById('botao-hih').addEventListener('click', function() {
    abrirJogo('HiH');
document.getElementById('botao-dress').addEventListener('click', function() {
    abrirJogo('DRESS');
