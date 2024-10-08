const likeButton = document.getElementById('like-button');
const likeCount = document.getElementById('like-count');
let liked = false;
let count = parseInt(likeCount.innerText);

// Função para enviar a atualização para o servidor
function atualizarCurtidas(curtidas) {
    fetch('https://digitalcore.azurewebsites.net/backend/curtidasJogos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            nome_jogo: 'DIGISMASH',  // ou pegue o nome do jogo dinamicamente
            curtidas_jogo: curtidas
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Curtidas atualizadas com sucesso');
        } else {
            console.error('Erro ao atualizar curtidas');
        }
    })
    .catch(error => console.error('Erro ao enviar a atualização:', error));
}

// Atualiza o contador de curtidas no frontend e backend
likeButton.addEventListener('click', function() {
    if (liked) {
        count--;
        likeButton.classList.remove('liked');
    } else {
        count++;
        likeButton.classList.add('liked');
    }
    liked = !liked;
    likeCount.innerText = count;

    // Envia a nova contagem de curtidas para o servidor
    atualizarCurtidas(count);
});
