function btncadastro(){
    window.location.href="./frontend/pages/cadastro.html";
}
function btnlogin(){
    window.location.href="./frontend/pages/login.html";
}

function userLogado() {
    // Carregar o arquivo JSON gerado pelo PHP
    fetch('https://digitalcore.azurewebsites.net/backend/ultimo_usuario.json')
        .then(response => response.json())  // Parse o JSON
        .then(data => {
            const nomeUser = data.nome_user;  // Nome do usuário no JSON
            const iconeUser = data.icone_user;  // Ícone do usuário no JSON

            // Elementos HTML
            const btnCad_Log = document.getElementById('user-controls');  // Botões de Cadastro e Login
            const btnLogado = document.getElementById('user-info');  // Div do usuário logado

            if (nomeUser && iconeUser) {
                // Se o usuário está logado, exibir a div com as informações do usuário
                btnLogado.style.display = 'block';  // Mostrar div do usuário logado
                btnCad_Log.style.display = 'none';  // Ocultar botões de cadastro e login

                // Atualizar o conteúdo da div com o nome e ícone do usuário
                document.getElementById('user-name').innerText = nomeUser;
                document.getElementById('user-icon').src = 'caminho/dos/icones/' + iconeUser;  // Caminho do ícone
            } else {
                // Caso não haja usuário logado, exibe os botões de cadastro e login
                btnLogado.style.display = 'none';
                btnCad_Log.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar o JSON:', error);
        });
}
