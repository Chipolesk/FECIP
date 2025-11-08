from tkinter import *
from tkinter import messagebox
import subprocess
from PIL import Image, ImageTk
import conection
import threading
import time

def carregar_imagem_icone(icone_user):
    try:
        img_path = "C:/fecip/img/" + icone_user
        img = Image.open(img_path)
        img = img.resize((30, 30), Image.LANCZOS)  # Redimensiona a imagem para 30x30 pixels
        return ImageTk.PhotoImage(img)
    except Exception as e:
        print(f"Erro ao carregar imagem {icone_user}: {e}")
        img = Image.open("C:/fecip/img/Pessoa_resized.png")
        img = img.resize((30, 30), Image.LANCZOS)  # Redimensiona a imagem padrão também
        return ImageTk.PhotoImage(img)

launcher = Tk()

launcher.title("Launcher")
icone_princ = ImageTk.PhotoImage(file="C:/fecip/img/LOGO.png")
launcher.iconphoto(TRUE, icone_princ)

# Obtém a largura e altura da tela
largura_tela = launcher.winfo_screenwidth()
altura_tela = launcher.winfo_screenheight()

# Define a geometria da janela para o tamanho da tela
launcher.geometry(f"{largura_tela}x{altura_tela}")

# Menu lateral
MenuLat = Frame(launcher, bg="#070b17", width=80, height=600)
MenuLat.pack(side="left", fill="both")

# PEGA O ICONE DO USUARIO QUE ESTÁ NO JSON FORNECIDO PELO SITE PARA EXIBIR NA FUNCAO JanelaPerfil
user_info = conection.pegar()
if user_info:  # Verifica se as informações foram obtidas
    icone_user = user_info['icone_user']  # Pega apenas o icone do json
    img_perfil = carregar_imagem_icone(icone_user)
else:
    img_perfil = carregar_imagem_icone("Pessoa_resized.png")

Perfil = Button(MenuLat, image=img_perfil, width=30, height=30, command=lambda: JanelaPerfil())
Perfil.pack(pady=5)

def atualizar_imagem():
    global img_perfil
    user_info = conection.pegar()
    if user_info:  # Verifica se as informações foram obtidas
        icone_user = user_info['icone_user']
        nova_img_perfil = carregar_imagem_icone(icone_user)
        if img_perfil != nova_img_perfil:
            img_perfil = nova_img_perfil
            Perfil.config(image=img_perfil)
    threading.Timer(10, atualizar_imagem).start()  # Verifica a cada 60 segundos

# Inicia a atualização automática da imagem
atualizar_imagem()


img_pesquisar = ImageTk.PhotoImage(Image.open("C:/fecip/img/lupa_resized.png"))
BtnPesquisar = Button(MenuLat, image=img_pesquisar, width=30, height=30, command= lambda: JanelaPesquisar())
BtnPesquisar.pack(pady=5)



# Barra de pesquisa
TopBarra = Frame(launcher, bg="#131c3b", width=largura_tela, height=80)
TopBarra.pack(side="top", fill="both")
BarraPesquisa = Entry(TopBarra, width=80)
BarraPesquisa.pack(pady=10)
BarraPesquisa.bind("<KeyRelease>", lambda event: AtualizaCatalogo(event))

# Espaçador à esquerda da area_principal para criar a margem
margem_areaPrincipal = Frame(launcher, bg="#2e303c", width=10)
margem_areaPrincipal.pack(side="left", fill="y")

# Área principal
area_principal = Frame(launcher, bg="#2e303c", width=800, height=800)
area_principal.pack(side="right", fill="both", expand=True)

# Frame para a barra de rolagem e a área de rolagem
frame_rolagem = Frame(area_principal)
frame_rolagem.pack(fill="both", expand=True)

# Canvas para a área de rolagem
quadro = Canvas(frame_rolagem, bg="#2e303c", highlightthickness=0, bd=0)
quadro.pack(side="top", fill="both", expand=True)

# Barra de rolagem horizontal
barra_rolagem = Scrollbar(frame_rolagem, orient="horizontal", command=quadro.xview, bg="#2e303c")
barra_rolagem.pack(side="bottom", fill="x")

# Configuração da área de rolagem para a barra de rolagem
quadro.configure(xscrollcommand=barra_rolagem.set)


# Frame interno que conterá os botões
quadro_interno = Frame(quadro, bg="#2e303c", width=400, height=400)  # Definindo tamanho específico
quadro_interno.pack(padx=100, side="top", fill="x", expand=True)
# Conectar o frame interno ao canvas em uma posição específica
quadro.create_window((900, 50), window=quadro_interno, anchor="n")  # Definindo posição específica

# Configurar a barra de rolagem para canvas
quadro.config(xscrollcommand=barra_rolagem.set)

# Lista de jogos personalizados com caminhos locais
jogos = [
    {"name": "DigiSmash", "path": "C:/fecip/JOGOS/DIGI/DigiSmash.exe", "icone": "C:/fecip/img/digismash.png"},
    {"name": "Shards take over", "path": "C:/fecip/JOGOS/STO/S.T.O.exe", "icone": "C:/fecip/img/sto.png"},
    {"name": "Hadvar in helheim", "path": "C:/fecip/JOGOS/HiH/hih2jogo.exe", "icone": "C:/fecip/img/hih.png"},
    {"name": "Dress O'Mama", "path": "C:/fecip/JOGOS/DRESS/Dress O'Mama.exe", "icone": "C:/fecip/img/dress o mama.png"},
    {"name": "CupCake Party", "path": "C:/fecip/JOGOS/CUPCAKE/CUPCAKE PARTY.exe", "icone": "C:/fecip/img/cupcake.png"},
    {"name": "JOGO 6", "path": "C:/fecip/JOGOS/FLAPPY/Flappy Wings.exe", "icone": "C:/fecip/img/flappy.png"},
   

]

BtnJogos = []  # Inicializa a lista fora da função
label_nao_encontrado = None  # Label para "não encontrado"

def Catalogo():
    global label_nao_encontrado
    catalog_label = Label(quadro_interno, text="Catálogo de Jogos", font=("Arial", 20, "bold"), bg="#2e303c", fg="white")
    catalog_label.grid(row=0, column=0, columnspan=2, pady=(60, 5), sticky="w")


    for index, jogo in enumerate(jogos):
        row = index // 6 + 1  # Calcula a linha
        col = index % 6  # Calcula a coluna
        icone = PhotoImage(file=jogo["icone"])
        btn_jogo = Button(quadro_interno, text="", width=205, height=205, image=icone, compound=TOP, command=lambda g=jogo: launch_jogo(g["path"]))
        btn_jogo.name = jogo["name"].lower()
        btn_jogo.image = icone  # Mantém uma referência à imagem
        btn_jogo.grid(row=row, column=col, padx=5, pady=(60, 5))
        BtnJogos.append(btn_jogo)

    # Cria o Label de "não encontrado" mas não o exibe
    label_nao_encontrado = Label(quadro_interno, text="Nenhum jogo encontrado", font=("Arial", 14, "bold"), bg="#2e303c", fg="red")

    # Ajustar o tamanho do canvas ao conteúdo
    quadro_interno.update_idletasks()
    quadro.config(scrollregion=quadro.bbox("all"))


def AtualizaCatalogo(event):
    BuscarTxt = BarraPesquisa.get().lower()
    resultados = 0
    
    for BtnAtt in BtnJogos:
        if BuscarTxt in BtnAtt.name:
            BtnAtt.grid()  # Mostra o botão novamente
            resultados += 1
        else:
            BtnAtt.grid_remove()  # Esconde o botão
    
    # Verifica se algum resultado foi encontrado
    if resultados == 0:
        label_nao_encontrado.grid(row=2, column=0, columnspan=3, padx=5, pady=5)
    else:
        label_nao_encontrado.grid_remove()

    # Ajustar o tamanho do canvas ao conteúdo
    quadro_interno.update_idletasks()
    quadro.config(scrollregion=quadro.bbox("all"))


def launch_jogo(path):
    subprocess.Popen(path)

Catalogo()

#BOTÕES LATERAIS

def JanelaPerfil():
    card_perfil = Toplevel()
    card_perfil.title("MEU PERFIL")
    card_perfil.geometry("700x400")

    # Carrega a imagem de fundo
    img_card = PhotoImage(file="C:/fecip/img/card_user.png")
    label_imagem = Label(card_perfil, image=img_card)
    label_imagem.pack()
    card_perfil.img_card = img_card


    def formatar_texto(texto, limite):
        linhas = []
        while len(texto) > limite:
            # Encontra o último espaço antes do limite para não quebrar no meio de uma palavra
            posicao = texto[:limite].rfind(' ')
            if posicao == -1:  # Caso não encontre espaço, quebra exatamente no limite
                posicao = limite
            linhas.append(texto[:posicao])
            texto = texto[posicao:].strip()
        linhas.append(texto)  # Adiciona a última parte do texto
        return '\n'.join(linhas)

    # Obtém as informações dos jogos jogados e formata o texto


   # Pegar as informações do último usuário logado
    user_info = conection.pegar()
    if user_info:  # Verifica se as informações foram obtidas
        nome_user = user_info['nome_user']
        icone_user = user_info['icone_user']

        jogos_jogados = user_info.get('jogos_jogados', "Nenhum jogo jogado")
        texto_formatado = formatar_texto(jogos_jogados, 30)

        try:
            img_perfil = PhotoImage(file="C:/fecip/img/" + icone_user)  
            label_perfil = Label(card_perfil, image=img_perfil)
            label_perfil.place(x=30, y=50, width=205, height=205)  
            card_perfil.img_perfil = img_perfil
        except Exception as e:
           # messagebox.showerror("ERRO", "ERRO AO CARREGAR A IMAGEM DO PERFIL")
            img_perfil = PhotoImage(file="C:/fecip/img/Pessoa_resized.png")
            label_perfil = Label(card_perfil, image=img_perfil)
            label_perfil.place(x=30, y=50, width=210, height=240)  
            card_perfil.img_perfil = img_perfil

        # Adiciona o nome (campo acima à direita)
        label_nome = Label(card_perfil, text=nome_user, font=("Arial", 16, "bold"), bg="#2e3b58", fg="white")
        label_nome.place(x=270, y=45, width=180, height=30)  
    else:
        messagebox.showwarning("Aviso", "Nenhum usuário logado.")

    # Adiciona outras informações (campo abaixo à direita)
    # Adiciona o label com o texto formatado
    label_info = Label(
        card_perfil, text=texto_formatado, 
        font=("Arial", 16, "bold"), bg="#2e3b58", fg="white", justify="left"
    )
    label_info.place(x=270, y=100, width=400, height=200)

    # Centraliza a janela na tela
    card_perfil.update_idletasks()  # Atualiza a janela para obter as dimensões corretas
    largura = card_perfil.winfo_width()
    altura = card_perfil.winfo_height()
    largura_perfil = card_perfil.winfo_screenwidth()
    altura_perfil = card_perfil.winfo_screenheight()
    x = (largura_perfil // 2) - (largura // 2)
    y = (altura_perfil // 2) - (altura // 2)
    card_perfil.geometry(f'{largura}x{altura}+{x}+{y}')

    # Executa o loop da janela
    card_perfil.mainloop()




def JanelaPesquisar():
    card_pesquisar = Toplevel()  # Criar a janela de pesquisa
    card_pesquisar.title("USUÁRIOS")
    card_pesquisar.geometry("1000x600")
    card_pesquisar.resizable(False, False)  # Impedir a janela de ser redimensionada

    usuarios = conection.AllUsers()  # Puxar usuários do banco de dados
    imagens = []  # Lista para armazenar referências às imagens

    # Criar um frame para conter o canvas e a barra de rolagem
    frame_canvas = Frame(card_pesquisar)
    frame_canvas.pack(fill=BOTH, expand=True)

    # Criar o canvas para os elementos roláveis
    canvas = Canvas(frame_canvas)
    canvas.pack(side=LEFT, fill=BOTH, expand=True)

    # Adicionar a barra de rolagem ao canvas
    scrollbar = Scrollbar(frame_canvas, orient=VERTICAL, command=canvas.yview)
    scrollbar.pack(side=RIGHT, fill=Y)

    # Configurar o canvas para trabalhar com a barra de rolagem
    canvas.configure(yscrollcommand=scrollbar.set)

    # Criar um frame dentro do canvas para organizar os widgets
    frame_in_canvas = Frame(canvas)
    canvas.create_window((0, 0), window=frame_in_canvas, anchor='nw')

    # Adicionar os elementos dentro do frame que está no canvas
    for index, usuario in enumerate(usuarios):
        icone_users = usuario[0]

        try:
            # Carregar e redimensionar a imagem do ícone do usuário
            img_path = "C:/fecip/img/" + icone_users
            img_user = Image.open(img_path)
            img_user = img_user.resize((70, 70), Image.LANCZOS)
            img_user = ImageTk.PhotoImage(img_user)
            imagens.append(img_user)  # Adicionar a imagem à lista de imagens
        except Exception as e:
            print(f"Erro ao carregar imagem {icone_users}: {e}")
            img_user = Image.new('RGB', (70, 70))  # Usar uma imagem vazia em caso de erro
            img_user = ImageTk.PhotoImage(img_user)
            imagens.append(img_user)

        # Carregar a imagem de fundo específica para cada usuário
        img_fundo_user = Image.open("C:/fecip/img/usuarios.png")
        img_fundo_user = ImageTk.PhotoImage(img_fundo_user)
        imagens.append(img_fundo_user)  # Adicionar a imagem à lista de imagens para evitar coleta de lixo

        # Criar um canvas para colocar a imagem de fundo e as informações do usuário
        canvas_user = Canvas(frame_in_canvas, width=1000, height=100, bg='white', highlightthickness=0)
        canvas_user.grid(row=index, column=0, padx=10)  # Adiciona margens entre os itens

        # Adicionar a imagem de fundo ao canvas do usuário
        canvas_user.create_image(0, 0, anchor='nw', image=img_fundo_user)

        # Adicionar o texto com as informações do usuário por cima da imagem de fundo
        canvas_user.create_text(150, 50, text=f"{usuario[1]:<5} | {usuario[2]}", fill="white", font=('arial', 16, 'bold'), anchor='w')

        # Criar um label para o ícone
        label_icon = Label(canvas_user, image=img_user, bg='black')
        label_icon.image = img_user  # Manter uma referência da imagem para evitar a coleta de lixo

        # Colocar o ícone na esquerda do canvas com margem
        canvas_user.create_window(70, 50, window=label_icon)

    # Ajustar a área rolável com base no tamanho do conteúdo
    frame_in_canvas.update_idletasks()
    canvas.config(scrollregion=canvas.bbox("all"))

    # Certifique-se de expandir o frame interno para que a rolagem funcione corretamente
    frame_in_canvas.bind(
        "<Configure>",
        lambda e: canvas.configure(scrollregion=canvas.bbox("all"))
    )

    # Centraliza a janela na tela
    card_pesquisar.update_idletasks()  # Atualiza a janela para obter as dimensões corretas
    largura = card_pesquisar.winfo_width()
    altura = card_pesquisar.winfo_height()
    largura_perfil = card_pesquisar.winfo_screenwidth()
    altura_perfil = card_pesquisar.winfo_screenheight()
    x = (largura_perfil // 2) - (largura // 2)
    y = (altura_perfil // 2) - (altura // 2)
    card_pesquisar.geometry(f'{largura}x{altura}+{x}+{y}')

    # Executa o loop da janela
    card_pesquisar.mainloop()





def start_flask_server():
    # Executa o servidor Flask
    try:
        subprocess.Popen(['python', 'C:/fecip/flask_server.py'])  
        print("Servidor Flask iniciado com sucesso.")
    except Exception as e:
        print(f"Erro ao iniciar o servidor Flask: {e}")

if __name__ == "__main__":
    start_flask_server()
    launcher.mainloop()