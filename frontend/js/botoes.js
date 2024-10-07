function btncadastro() {
    window.location.href = "./frontend/pages/cadastro.html";
}

function btnlogin() {
    window.location.href = "./frontend/pages/login.html";
}

function btncadastroJOGOS() {
    window.location.href = "./cadastro.html";
}

function btnloginJOGOS() {
    window.location.href = "./login.html";
}

function userLogado() {
    fetch('https://digitalcore.azurewebsites.net/backend/ultimo_usuario.json', {
        method: 'GET',  
        mode: 'cors',   
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta da requisição');
        }
        return response.json();  // Parse o JSON
    })
    .then(data => {
        const nomeUser = data.nome_user;  // Nome do usuário no JSON
        console.log(nomeUser);

        const btnCad_Log = document.getElementById('user-controls');  // Botões de Cadastro e Login
        const btnLogado = document.getElementById('user-info');  // Div do usuário logado

        if (nomeUser && nomeUser !== 'Nenhum usuário logado') {
            btnLogado.style.display = 'block';  // Mostrar div do usuário logado
            btnCad_Log.style.display = 'none';  // Ocultar botões de cadastro e login
            document.getElementById('username').innerText = nomeUser;
        } else {
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
    document.getElementById('username').innerText = 'Nenhum usuário logado';

    // Enviar requisição ao backend para limpar o usuário logado
    fetch('https://digitalcore.azurewebsites.net/backend/logout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao deslogar');
        }
        return response.json();
    })
    .then(data => {
        console.log('Usuário deslogado com sucesso', data);
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

document.addEventListener('DOMContentLoaded', userLogado);
