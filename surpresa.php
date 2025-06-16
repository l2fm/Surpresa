<?php
// Conexão com o banco de dados
$host = 'localhost';
$user = 'root';     // ajuste conforme seu ambiente
$pass = '';         // coloque a senha do MySQL se houver
$dbname = 'galeria_namorados';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

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
            // Gravar no banco
            $stmt = $conn->prepare("INSERT INTO fotos (caminho) VALUES (?)");
            $stmt->bind_param("s", $destino);
            $stmt->execute();
            $stmt->close();

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
  <title>Galeria com Banco de Dados</title>
  <style>
    /* ... (mesmo CSS anterior omitido para focar na lógica) ... */
    /* use o CSS já gerado anteriormente */
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
        $result = $conn->query("SELECT caminho FROM fotos ORDER BY data_envio DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<img src='" . htmlspecialchars($row['caminho']) . "' alt='Foto'>";
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
<?php $conn->close(); ?>
