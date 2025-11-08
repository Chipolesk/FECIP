from flask import Flask, request, jsonify
import subprocess
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

# Função que executa o jogo com base no nome e exibe o usuário
def executar_jogo(nome_jogo):
    if nome_jogo == "DigiSmash":
        print(f"Abrindo o DigiSmash.")
        result = subprocess.run(["C:/fecip/JOGOS/DIGI/DigiSmash.exe"], capture_output=True, text=True)
    elif nome_jogo == "STO":
        print(f"Abrindo o STO.")
        result = subprocess.run(["C:/fecip/JOGOS/STO/S.T.O.exe"], capture_output=True, text=True)
    elif nome_jogo == "HiH":
        print(f"Abrindo o HiH.")
        result = subprocess.run(["C:/fecip/JOGOS/HiH/hih2jogo.exe"], capture_output=True, text=True)
    elif nome_jogo == "DRESS":
        print(f"Abrindo o Dress.")
        result = subprocess.run(["C:/fecip/JOGOS/DRESS/Dress O'Mama.exe"], capture_output=True, text=True)
    elif nome_jogo == "CUPCAKE":
        print(f"Abrindo o CUPCAKE.")
        result = subprocess.run(["C:/fecip/JOGOS/CUPCAKE/Cupcake Party.exe"], capture_output=True, text=True)
    elif nome_jogo == "FLAPPY":
        print(f"Abrindo o Flappy.")
        result = subprocess.run(["C:/fecip/JOGOS/FLAPPY/Flappy Wings.exe"], capture_output=True, text=True)
    else:
        print(f"Jogo {nome_jogo} não encontrado.")
        return

    if result.returncode != 0:
        print(f"Erro ao abrir o jogo: {result.stderr}")
    else:
        print(f"Jogo {nome_jogo} executado com sucesso.")


@app.route('/abrir_jogo', methods=['POST'])
def abrir_jogo():
    dados = request.get_json()

    # Verifique se 'jogo' está no dicionário
    if 'jogo' not in dados:
        return jsonify({'error': 'Jogo não especificado'}), 400

    jogo = dados['jogo']
    
    # Chamar a função para executar o jogo
    executar_jogo(jogo)

    return jsonify({'message': f'Jogo {jogo} aberto com sucesso!'}), 200

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
