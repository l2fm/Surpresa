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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      padding: 0;
      background: #ffe6f0;
      font-family: 'Segoe UI', sans-serif;
      position: relative;
    }

    main {
      padding: 20px;
      max-width: 1000px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      color: #d63384;
      font-size: 2.2em;
      text-align: center;
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
      max-width: 90%;
    }

    form {
      margin-top: 30px;
      text-align: center;
      width: 100%;
    }

    input[type="file"] {
      margin: 10px auto;
    }

    input[type="submit"] {
      margin-top: 10px;
      padding: 10px 20px;
      border: none;
      background: #ff6699;
      color: white;
      border-radius: 10px;
      cursor: pointer;
    }

    .mensagem-upload {
      margin-top: 10px;
      color: green;
    }

    .galeria {
      margin-top: 30px;
      display: none;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
      width: 100%;
    }

    .galeria img {
      width: 180px;
      height: 180px;
      object-fit: cover;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: transform 0.3s;
      cursor: pointer;
    }

    .galeria img:hover {
      transform: scale(1.05);
    }

    .zoom-fundo {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.8);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 999;
      display: none;
    }

    .zoom-fundo img {
      max-width: 90%;
      max-height: 90%;
      border-radius: 10px;
    }

    .heart {
      position: absolute;
      width: 20px;
      height: 20px;
      background: red;
      transform: rotate(45deg);
      animation: float 8s infinite ease-in;
      z-index: 998;
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
      0%   { transform: translateY(0) rotate(45deg); opacity: 1; }
      100% { transform: translateY(-100vh) rotate(45deg); opacity: 0; }
    }

    @media (max-width: 768px) {
      .galeria img { width: 45%; height: auto; }
      h1 { font-size: 1.8em; }
      #btn { font-size: 0.9em; padding: 10px 20px; }
    }

    @media (max-width: 480px) {
      .galeria img { width: 100%; height: auto; }
      h1 { font-size: 1.5em; }
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
      <input type="file" name="foto" accept="image/*" required><br>
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

  <!-- Modal de Zoom -->
  <div class="zoom-fundo" id="zoom">
    <img src="" id="zoomImg" alt="Imagem Ampliada" />
  </div>

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

    // Zoom ao clicar
    const imgs = document.querySelectorAll('.galeria img');
    const zoom = document.getElementById('zoom');
    const zoomImg = document.getElementById('zoomImg');

    imgs.forEach(img => {
      img.addEventListener('click', () => {
        zoomImg.src = img.src;
        zoom.style.display = 'flex';
      });
    });

    zoom.addEventListener('click', () => {
      zoom.style.display = 'none';
    });

    // Corações no clique ou toque
    document.addEventListener('click', criarCoracao);
    document.addEventListener('touchstart', criarCoracao);

    function criarCoracao(e) {
      const heart = document.createElement('div');
      heart.classList.add('heart');
      heart.style.left = (e.clientX || e.touches[0].clientX) + 'px';
      heart.style.top = (e.clientY || e.touches[0].clientY) + 'px';
      heart.style.animationDuration = 4 + Math.random() * 4 + 's';
      document.body.appendChild(heart);
      setTimeout(() => heart.remove(), 8000);
    }
  </script>
</body>
</html>
