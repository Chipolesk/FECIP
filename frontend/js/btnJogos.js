function abrirJogo(jogo) {
    const nickname = localStorage.getItem('nickname');
    const iconeUser = localStorage.getItem('icone_user');

    // Verifique se os dados necessários estão disponíveis
    if (!nickname) {
        console.error('Nickname do usuário não encontrado no localStorage.');
        return; // Retorna sem fazer a requisição
    }
    if (!iconeUser){
         console.error('ícone do usuário não encontrado no localStorage.');
        return;
    }

    fetch('http://localhost:5000/abrir_jogo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ jogo: jogo, nickname: nickname, icone_user: iconeUser }),
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
