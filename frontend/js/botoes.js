function btncadastro(){
    window.location.href="./frontend/pages/cadastro.html";
}
function btnlogin(){
    window.location.href="./frontend/pages/login.html";
}

// Variável para armazenar o estado do usuário (se está logado ou não)
let estaLogado = false; // Inicialmente, o usuário não está logado
let userNickname = ''; // Variável para armazenar o nickname do usuário

// Função para atualizar a interface do usuário
function AttPagina() {
    // Recupera o estado do login do localStorage
    EstaLogado = localStorage.getItem('EstaLogado') === 'true';
    userNickname = localStorage.getItem('nickname') || ''; // Recupera o nickname do localStorage

    const btnGroup = document.querySelector('.container-btn');
    btnGroup.innerHTML = ''; // Limpa os botões existentes

    if (EstaLogado) {
        // Se o usuário está logado, exibe o nome do usuário e o botão de sair
        btnGroup.innerHTML = `
            <span class="navbar-text">Bem-vindo, ${userNickname}!</span>
            <button class="btn btn-outline-danger" onclick="logout()">SAIR</button>
        `;

        // Exibe o nome de usuário no elemento <span>
        const userInfo = document.getElementById('user-info');
        const usernameSpan = document.getElementById('username');
        usernameSpan.textContent = userNickname; // Atualiza o conteúdo do <span>
        userInfo.style.display = 'block'; // Exibe a seção de informações do usuário
    } else {
        // Se o usuário não está logado, exibe os botões de cadastro e login
        btnGroup.innerHTML = `
            <button class="btn btn-outline-success" onclick="btncadastro()">CADASTRAR</button>
            <button class="btn btn-outline-success" onclick="btnlogin()">LOGAR</button>
        `;

        // Esconde a seção de informações do usuário
        document.getElementById('user-info').style.display = 'none';
    }
}

// Função chamada após o cadastro
function usuarioRegistrado(nickname) {
    estaLogado = true; // Atualiza o estado para logado
    localStorage.setItem('estaLogado', 'true'); // Armazena o estado no localStorage
    localStorage.setItem('nickname', nickname); // Armazena o nickname no localStorage
    atualizaPagina(); // Atualiza a interface
}

// Função chamada ao sair
function logout() {
    estaLogado = false; // Atualiza o estado para não logado
    localStorage.setItem('estaLogado', 'false'); // Armazena o estado no localStorage
    localStorage.removeItem('nickname'); // Remove o nickname do localStorage
    atualizaPagina(); // Atualiza a interface
}

// Chama a função para configurar a interface inicialmente
atualizaPagina();
