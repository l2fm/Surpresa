<?php
$uploadDir = 'imagens/';
$mensagemUpload = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $file = $_FILES['foto'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($ext, $allowed) && $file['error'] === 0) {
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
  <title>Galeria do Dia dos Namorados</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #ffe6f0;
      font-family: 'Segoe UI', sans-serif;
    }

    main {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    h1 {
      color: #d63384;
      font-size: 2.5em;
      text-align: center;
      margin-bottom: 20px;
    }

    #btn {
      padding: 12px 24px;
      background: #ff4d88;
      color: white;
      border: none;
      border-radius: 30px;
      font-size: 1em;
      cursor: pointer;
    }

    #btn:hover {
      background: #e6005c;
    }

    #mensagem {
      margin-top: 20px;
      font-size: 1.2em;
      color: #660033;
      text-align: center;
      display: none;
      max-width: 80%;
    }

    .galeria {
      margin-top: 30px;
      display: none;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
    }

    .galeria img {
      width: 180px;
      height: 180px;
      object-fit: cover;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: transform 0.3s;
    }

    .galeria img:hover {
      transform: scale(1.05);
    }

    form {
      margin-top: 30px;
      text-align: center;
    }

    input[type="file"] {
      margin: 10px auto;
    }

    .mensagem-upload {
      margin-top: 10px;
      color: green;
    }

    .heart {
      position: absolute;
      width: 20px;
      height: 20px;
      background: red;
      transform: rotate(45deg);
      animation: float 8s infinite ease-in;
    }

    .heart::before,
    .heart::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      background: red;
      border-radius: 50%;
    }

    .heart::before { top: -10px; left: 0; }
    .heart::after  { top: 0; left: -10px; }

    @keyframes float {
      0% {
        transform: translateY(0) rotate(45deg);
        opacity: 1;
      }
      100% {
        transform: translateY(-100vh) rotate(45deg);
        opacity: 0;
      }
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
      <br>
      <input type="submit" value="Enviar Foto">
    </form>
    <?php if (!empty($mensagemUpload)) echo "<div class='mensagem-upload'>$mensagemUpload</div>"; ?>

    <div class="galeria" id="galeria">
      <?php
        $arquivos = glob("imagens/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        foreach ($arquivos as $img) {
            echo "<img src='$img' alt='Foto'>";
        }
      ?>
    </div>
  </main>

  <script>
    const btn = document.getElementById('btn');
    const msg = document.getElementById('mensagem');
    const galeria = document.getElementById('galeria');

    const frases = [
      "Você é meu hoje, meu amanhã e meu sempre. ❤️",
      "Te amar é a melhor parte do meu dia. 🌹",
      "Com você, todos os dias são especiais. 💑",
      "O amor da minha vida tem seu nome. 💖",
      "Nos seus olhos encontrei meu lar. ✨",
      "Você me completa de um jeito que ninguém mais conseguiria. 💞",
      "Amar você é como respirar: simplesmente acontece. 💓",
      "Desde que te conheci, até o silêncio tem mais significado. 💗"
    ];

    let index = 0;
    btn.addEventListener('click', () => {
      galeria.style.display = 'flex';
      msg.style.display = 'block';
      msg.textContent = frases[index];
      index = (index + 1) % frases.length;
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
