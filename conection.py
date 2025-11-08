import pyodbc
import json
import requests

def conexaoDB():
    server = 'digitalcoreserver.database.windows.net'  # Nome do servidor Azure
    database = 'DigitalCoreDB'                           # Nome do banco de dados
    username = 'DIGITAL.CORE'                            # Nome de usuário
    password = '@FECIP2K24'                              # Senha
    driver = '{ODBC Driver 17 for SQL Server}'          # Driver ODBC

    conn_str = f"DRIVER={driver};SERVER={server};DATABASE={database};UID={username};PWD={password}"

    try:
        conn = pyodbc.connect(conn_str)
        print("Conexão bem-sucedida!")
        return conn
    except Exception as e:
        print("Erro ao conectar:", e)


# Função para gravar o último usuário logado em um arquivo JSON
def gravar_usuario(usuario):
    caminho_arquivo = 'ultimo_usuario.json'
    
    # Estrutura do dado que será gravado
    dados = {
        'nome_user': usuario['nome_user'],
        'icone_user': usuario['icone_user'],
        'jogos_jogados': usuario['jogos_jogados']
    }
    
    # Escreve os dados no arquivo JSON
    with open(caminho_arquivo, 'w') as arquivo:
        json.dump(dados, arquivo)

# Função para pegar os dados do último usuário logado a partir do arquivo JSON
def pegar():
    url = 'https://digitalcore.azurewebsites.net/backend/ultimo_usuario.json'  # URL do seu arquivo JSON
    user_info = {}
    
    try:
        response = requests.get(url)
        response.raise_for_status()  # Verifica se houve erro na requisição
        user_info = response.json()  # Faz o parse do JSON da resposta
    except requests.exceptions.RequestException as e:
        print(f"Erro ao acessar o JSON: {e}")
    
    return user_info

#Função para pegar todos os usuarios logados e exibir na janelaPesquisar
def AllUsers():
    conexao = conexaoDB()
    cursor = conexao.cursor()
    sqlCommand = "SELECT icone_user, nome_user, jogos_jogados FROM digitalcore.usuario ORDER BY id DESC"
    cursor.execute(sqlCommand)
    retorno = cursor.fetchall()
    cursor.close()
    conexao.close()

    return retorno

