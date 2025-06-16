<?php
$uploadDir = 'imagens/';
$mensagemUpload = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $file = $_FILES['foto'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($ext, $allowed) && $file['error'] === 0 && getimagesize($file['tmp_name'])) {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $novoNome = uniqid("foto_", true) . "." . $ext;
        $destino = $uploadDir . $novoNome;

        if (move_uploaded_file($file['tmp_name'], $destino)) {
            $mensagemUpload = "Imagem enviada com sucesso!";
        } else {
            $mensagemUpload = "Erro ao salvar a imagem.";
        }
    } else {
        $mensagemUpload = "Tipo de arquivo inválido ou erro no upload.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Galeria do Dia dos Namorados 💘</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fff0f6;
      text-align: center;
      padding: 20px;
    }
    h1 {
      color: #d63384;
    }
    #mensagem {
      margin: 20px;
      font-size: 20px;
      color: #ff0066;
    }
    form {
      margin: 20px 0;
    }
    .mensagem-upload {
      color: green;
      font-weight: bold;
    }
    .galeria {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
      margin-top: 20px;
    }
    .galeria img {
      width: 150px;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    .heart {
      position: fixed;
      top: -50px;
      width: 30px;
      height: 30px;
      background: red;
      transform: rotate(45deg);
      animation: flutuar 6s linear infinite;
    }
    .heart::before,
    .heart::after {
      content: "";
      position: absolute;
      width: 30px;
      height: 30px;
      background: red;
      border-radius: 50%;
    }
    .heart::before {
      top: -15px;
      left: 0;
    }
    .heart::after {
      top: 0;
      left: -15px;
    }
    @keyframes flutuar {
      0% { transform: translateY(0) rotate(45deg); opacity: 1; }
      100% { transform: translateY(-100vh) rotate(45deg); opacity: 0; }
    }
  </style>
</head>
<body>
  <main>
    <h1>Feliz Dia dos Namorados 💘</h1>
    <button id="btn">Clique para ver uma surpresa 💌</button>
    <div id="mensagem"></div>

    <form method="POST" enctype="multipart/form-data">
      <label>Envie sua foto especial:</label><br>
      <input type="file" name="foto" accept="image/*" required>
      <br><br>
      <input type="submit" value="Enviar Foto">
    </form>
    <?php if (!empty($mensagemUpload)) echo "<div class='mensagem-upload'>$mensagemUpload</div>"; ?>

    <div class="galeria" id="galeria">
      <?php
        if (is_dir($uploadDir)) {
          $arquivos = array_diff(scandir($uploadDir, SCANDIR_SORT_DESCENDING), ['.', '..']);
          foreach ($arquivos as $arquivo) {
              $caminho = $uploadDir . $arquivo;
              echo "<img src='" . htmlspecialchars($caminho) . "' alt='Foto'>";
          }
        }
      ?>
    </div>
  </main>

  <script>
    const btn = document.getElementById('btn');
    const msg = document.getElementById('mensagem');
    const galeria = document.getElementById('galeria');

    const todasFrases = [
      "Você é meu hoje, meu amanhã e meu sempre. ❤️",
      "Te amar é a melhor parte do meu dia. 🌹",
      "Com você, todos os dias são especiais. 💑",
      "O amor da minha vida tem seu nome. 💖",
      "Nos seus olhos encontrei meu lar. ✨",
      "Te escolher foi o melhor que já fiz. 💘",
      "Você me completa de um jeito que ninguém mais conseguiria. 💞",
      "Amar você é como respirar: simplesmente acontece. 💓",
      "Desde que te conheci, até o silêncio tem mais significado. 💗"
    ];

    let frases = [];
    let index = 0;

    function embaralharFrases() {
      frases = [...todasFrases].sort(() => Math.random() - 0.5);
      index = 0;
    }

    embaralharFrases();

    btn.addEventListener('click', () => {
      msg.style.display = 'block';
      galeria.style.display = 'flex';

      msg.innerText = frases[index];
      index++;

      if (index >= frases.length) {
        setTimeout(() => {
          embaralharFrases();
          msg.innerText = "Vamos de novo? Porque meu amor por você nunca acaba! 💘";
        }, 2000);
      }
    });

    function criarCoracao() {
      const heart = document.createElement('div');
      heart.classList.add('heart');
      heart.style.left = Math.random() * 100 + 'vw';
      heart.style.animationDuration = 4 + Math.random() * 4 + 's';
      document.body.appendChild(heart);
      setTimeout(() => heart.remove(), 8000);
    }

    setInterval(criarCoracao, 300);
  </script>
</body>
</html>
