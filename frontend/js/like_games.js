// Seleciona todos os elementos de jogos
const jogos = document.querySelectorAll('.game-card');

jogos.forEach(jogo => {
    const nomeJogo = jogo.querySelector('.nome-jogo').dataset.nomeJogo; // Pega o nome do jogo dinamicamente
    const likeButton = jogo.querySelector('.fa-thumbs-up'); // Ícone de like
    const likeCountElement = jogo.querySelector('.like-count'); // Contador de likes
    let liked = false;
    let count = parseInt(likeCountElement.innerText) || 0;

    // Função para buscar o número de curtidas do backend
    function fetchLikes() {
        fetch(`https://digitalcore.azurewebsites.net/backend/curtidasJogos.php?nome_jogo=${encodeURIComponent(nomeJogo)}`)
            .then(response => response.json())
            .then(data => {
                count = parseInt(data.curtidas_jogo) || 0;
                likeCountElement.innerText = count;
            })
            .catch(error => console.error('Erro ao buscar os likes:', error));
    }

    // Função para atualizar o número de curtidas no backend
    function updateLikes(newCount) {
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
                likeCountElement.innerText = newCount;
            } else {
                console.error('Erro ao atualizar os likes:', data.message);
            }
        })
        .catch(error => console.error('Erro ao atualizar os likes:', error));
    }

    // Inicializa a contagem de curtidas ao carregar a página
    fetchLikes();

    // Evento de clique no botão de like
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
});
