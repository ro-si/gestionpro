<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>

    <!-- Navigation Bar -->
    <div class="navbar">


        <div class="ic">
            <i id="icon" class="fa-solid fa-user"></i>
            <ul id="menu" class="hidden">
                <li><a href="pages/authentification.php"></i> Se connecter</a></li>

            </ul>
        </div>

    </div>


    <section>
        <div class="container">
            <div class="circle"></div>
        </div>

        <main>

            <h1 id="p2">Bienvenue</h1>
            <p id="demo"></p>
            <div class="boot">
                <button>Se connecter</button>
            </div>
        </main>
    </section>


    <footer>




    </footer>

    <script>
        let text = "Bienvenue sur PROGESTION votre application qui vous permet de suivre votre entreprise de près"; // String written inside quotes
        document.getElementById("demo").innerHTML = text;
        document.getElementById("demo").style.color = "black";

        /*h1  EN javascript*/
        document.getElementById("p2").style.color = "black";



        document.getElementById('icon').addEventListener('click', function() {
            var menu = document.getElementById('menu');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        });
    </script>


</body>

</html>