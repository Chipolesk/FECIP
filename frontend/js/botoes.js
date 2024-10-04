function btncadastro() {
    window.location.href = "./frontend/pages/cadastro.html";
}

function btnlogin() {
    window.location.href = "./frontend/pages/login.html";
}

function userLogado() {
    // Carregar o arquivo JSON gerado pelo PHP
    fetch('https://digitalcore.azurewebsites.net/backend/ultimo_usuario.json')
        .then(response => response.json())  // Parse o JSON
        .then(data => {
            const nomeUser = data.nome_user;  // Nome do usuário no JSON 
            console.log(nomeUser);

            // Elementos HTML
            const btnCad_Log = document.getElementById('user-controls');  // Botões de Cadastro e Login
            const btnLogado = document.getElementById('user-info');  // Div do usuário logado

            if (nomeUser) {
                // Se o usuário está logado, exibir a div com as informações do usuário
                btnLogado.style.display = 'block';  // Mostrar div do usuário logado
                btnCad_Log.style.display = 'none';  // Ocultar botões de cadastro e login

                // Atualizar o conteúdo da div com o nome do usuário
                document.getElementById('username').innerText = nomeUser;
            } else {
                // Caso não haja usuário logado, exibe os botões de cadastro e login
                btnLogado.style.display = 'none';
                btnCad_Log.style.display = 'block';
                console.log('Nenhum usuário logado');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar o JSON:', error);
        });
}

// Função para simular logout (limpa a interface e volta a mostrar os botões de cadastro/login)
function logout() {
    const btnCad_Log = document.getElementById('user-controls');
    const btnLogado = document.getElementById('user-info');

    // Limpar a interface e voltar ao estado de não logado
    btnLogado.style.display = 'none';
    btnCad_Log.style.display = 'block';
    document.getElementById('username').innerText = '';
    
    // Aqui você pode também implementar a lógica para limpar o usuário logado no backend, se necessário
}
document.addEventListener('DOMContentLoaded', userLogado);

