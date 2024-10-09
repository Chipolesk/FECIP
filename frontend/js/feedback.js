document.querySelector('.input-coment button').addEventListener('click', function() {
    const feedbackText = document.querySelector('#text').value;

    if (feedbackText.trim() !== "") {
        // Faz uma requisição POST para o servidor
        fetch('https://digitalcore.azurewebsites.net/backend/feedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ feedback: feedbackText }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                alert('Comentário enviado com sucesso!');
                document.querySelector('#text').value = ""; // Limpa o campo de texto
            } else {
                alert('Erro ao enviar comentário: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao enviar comentário.');
        });
    } else {
        alert('O comentário não pode estar vazio.');
    }
});
