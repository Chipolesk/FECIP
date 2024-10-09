const likeButton = document.getElementById('like-button');
const likeCount = document.getElementById('like-count');
const nomeJogoElement = document.getElementById('nome-jogo');
let liked = false;
let count = parseInt(likeCount.innerText) || 0; // Adiciona um valor padrão de 0 caso o innerText não seja um número

// Função para buscar o número de curtidas do backend
function fetchLikes() {
    const nomeJogo = nomeJogoElement.innerText; // Pega o nome do jogo dinamicamente
    fetch(https://digitalcore.azurewebsites.net/backend/curtidasJogos.php?nome_jogo=${encodeURIComponent(nomeJogo)})
        .then(response => response.json())
        .then(data => {
            count = parseInt(data.curtidas_jogo) || 0;
            likeCount.innerText = count;
        })
        .catch(error => console.error('Erro ao buscar os likes:', error));
}

// Função para atualizar o número de curtidas no backend
function updateLikes(newCount) {
    const nomeJogo = nomeJogoElement.innerText; // Pega o nome do jogo dinamicamente
    fetch('https://digitalcore.azurewebsites.net/backend/curtidasJogos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ nome_jogo: nomeJogo, curtidas_jogo: newCount })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'sucesso') {
            likeCount.innerText = newCount;
        } else {
            console.error('Erro ao atualizar os likes:', data.message);
        }
    })
    .catch(error => console.error('Erro ao atualizar os likes:', error));
}

// Inicializa a contagem de curtidas ao carregar a página
document.addEventListener('DOMContentLoaded', fetchLikes);

likeButton.addEventListener('click', function() {
    if (liked) {
        count--;
        likeButton.classList.remove('liked');
    } else {
        count++;
        likeButton.classList.add('liked');
    }
    liked = !liked;
    updateLikes(count);
});
