<?php
// dia_dos_namorados.php
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Feliz Dia dos Namorados 💖</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #ffe6f0;
      font-family: 'Segoe UI', sans-serif;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
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
      transition: background 0.3s ease;
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

    .heart::before {
      top: -10px;
      left: 0;
    }

    .heart::after {
      top: 0;
      left: -10px;
    }

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
  <h1>Feliz Dia dos Namorados 💘</h1>
  <button id="btn">Clique para ver uma surpresa 💌</button>
  <div id="mensagem">Você é a razão do meu sorriso todos os dias. Te amo! ❤️</div>

  <script>
    const btn = document.getElementById('btn');
    const msg = document.getElementById('mensagem');

    btn.addEventListener('click', () => {
      msg.style.display = 'block';
    });

    // Função para criar corações flutuando
    function criarCoracao() {
      const heart = document.createElement('div');
      heart.classList.add('heart');
      heart.style.left = Math.random() * 100 + 'vw';
      heart.style.animationDuration = 4 + Math.random() * 4 + 's';
      document.body.appendChild(heart);

      setTimeout(() => {
        heart.remove();
      }, 8000);
    }

    setInterval(criarCoracao, 300);
  </script>
</body>
</html>
