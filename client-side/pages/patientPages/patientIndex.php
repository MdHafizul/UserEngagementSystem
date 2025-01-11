<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Naluri - Empowering Mental Wellness</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    /* General Reset */
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      transition: background-color 1s ease; /* Smooth background color transition */
    }

    /* Full Page Layout */
    .landing-header {
      color: white;
      text-align: center;
      opacity: 0;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
    }

    /* Circular Progress Bar Container */
    .progress-circle-container {
      width: 150px;
      height: 150px;
      position: absolute;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
      background-color: transparent;
      border: 4px solid #00A8A1;
      animation: rotateCircle 1.5s infinite linear;
    }

    .progress-circle {
      width: 100px;
      height: 100px;
      border: 8px solid transparent;
      border-top-color: #00A8A1;
      border-radius: 50%;
      animation: spinProgress 1.5s linear forwards;
    }

    /* Keyframe for circular spinning animation (1.5s duration) */
    @keyframes rotateCircle {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }

    /* Progress Circle Spin Effect (1.5s duration) */
    @keyframes spinProgress {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }

    /* Typed Text Animation */
    .typed-text {
      font-size: 3.5rem;
      font-weight: 700;
      margin-top: 20px;
      white-space: nowrap;
      overflow: hidden;
      border-right: 3px solid #00A8A1;
      width: 0;
      animation: typing 4s steps(40) 1.5s forwards, blink 0.75s step-end infinite;
    }

    /* Keyframe for typing effect */
    @keyframes typing {
      from {
        width: 0;
      }
      to {
        width: 100%; /* Full width after typing animation */
      }
    }

    /* Keyframe for blinking cursor */
    @keyframes blink {
      0% {
        border-color: transparent;
      }
      50% {
        border-color: #00A8A1;
      }
      100% {
        border-color: transparent;
      }
    }

    /* Text Styling */
    .landing-header p {
      font-size: 1.5rem;
      margin-bottom: 30px;
      opacity: 0.85;
    }

    .landing-header .btn {
      background: linear-gradient(135deg, #4BCBBD, #2A9D8F);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      padding: 15px 30px;
      border-radius: 50px;
      border: none;
      transition: background 0.3s ease, transform 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .landing-header .btn:hover {
      background: linear-gradient(135deg, #2A9D8F, #4BCBBD);
      transform: translateY(-4px);
    }

  </style>
</head>

<body>

  <!-- Circular Progress Bar -->
  <div class="progress-circle-container">
    <div class="progress-circle"></div>
  </div>

  <!-- Landing Header Section -->
  <header class="landing-header">
    <div class="container text-center">
      <div class="typed-text">Naluri - Empowering Mental Wellness</div>
      <p>Welcome to Naluri. Your journey to a healthier mind starts here.</p>
      <a href="recommendation.php" class="btn">Get Started</a>
    </div>
  </header>

<script>
  window.onload = function () {
    // Show Circular Progress Bar first
    const progressCircle = document.querySelector('.progress-circle');
    const landingHeader = document.querySelector('.landing-header');
    const typedText = document.querySelector('.typed-text');
    const body = document.querySelector('body');

    // Show the landing header after 3 seconds (loading time)
    setTimeout(() => {
      // Change background color to green with smooth transition
      body.style.backgroundColor = "#00A8A1"; // Green color

      // Show the landing page header
      landingHeader.style.opacity = '1';

      // Hide the circular progress bar
      progressCircle.parentNode.style.display = 'none'; 

      // Ensure the typed-text width stays at 100% for smooth final appearance
      typedText.style.width = '100%'; // This makes sure the text remains fully visible

    }, 3000); // Adjust the timing if necessary
  }
</script>

</body>

</html>
