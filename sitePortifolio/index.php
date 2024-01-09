<!DOCTYPE html>
<html>
<head>
    <title>Seja Bem-vindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        
    </style>
</head>
<body>
    <header id="topo">
        <img src="./imagens/logo.png">
    </header>
    
    <nav>
		<a href="#sobremim">Sobre mim</a>
		<a href="#meusprojetos">Meus Projetos</a>
    </nav>

	<section id="sobremim">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6">
					<div class="image-container">
						<img class="profile-image" src="./imagens/fotoeu.png">
					</div>
				</div>
				<div class="col-md-6">
					<ul class="skills">
						<li><b>Nome:</b> Rodrigo</li>
						<li><b>Graduação:</b> Análise e Desenvolvimento de Sistemas (cursando)</li>
						<li><b>Linguagens e ferramentas:</b></li>
							<ul>
								<li>Front-end</li>
								<ul>
									<li>HTML e CSS</li>
								</ul>
							</ul>
							<ul>
								<li>Back-end</li>
								<ul>
									<li>PHP, Python e Java</li>
								</ul>
							</ul>
					</ul>
				</div>
			</div>
		</div>
	</section>


    <section id="meusprojetos">
            <div class="container">
                <h2 style="margin-bottom: 20px; text-align: center; color:white">Meus Projetos</h2>
				<div class="row align-items-center">
					<div class="col">
						<a href="#">
							<div class="card">
								<img src="./imagens/web.svg" alt="Avatar" style="width:100%">
								<div class="container">
									<h4><b>Programação web</b></h4>
								</div>
							</div>
						</a>
					</div>
					
					<div class="col">
						<a href="#">
							<div class="card">
								<img src="./imagens/python.png" alt="Avatar" style="width:100%">
								<div class="container">
									<h4><b>Python</b></h4>
								</div>
							</div>
						</a>
					</div>
					
					<div class="col">
						<a href="#">
							<div class="card">
								<img src="./imagens/java.png" alt="Avatar" style="width:100%">
								<div class="container">
									<h4><b>Java</b></h4>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
    </section>
	
	<div class="b-example-divider"></div>

	<section class="rodape">
		<div class="container">
			<footer class="py-5">
				<div class="row">
					<div class="col-6 col-md-2 mb-3">
						<h5>Section</h5>
						<ul class="nav flex-column">
							<li><a href="#">Home</a></li>
							<li><a href="#" >Features</a></li>
							<li><a href="#">Pricing</a></li>
							<li><a href="#">FAQs</a></li>
							<li><a href="#">About</a></li>
						</ul>
					</div>

					<div class="col-6 col-md-2 mb-3">
						<h5>Section</h5>
						<ul class="nav flex-column">
							<li><a href="#">Home</a></li>
							<li><a href="#" >Features</a></li>
							<li><a href="#">Pricing</a></li>
							<li><a href="#">FAQs</a></li>
							<li><a href="#">About</a></li>
						</ul>
					</div>

					<div class="col-6 col-md-2 mb-3">
						<h5>Section</h5>
						<ul class="nav flex-column">
							<li><a href="#">Home</a></li>
							<li><a href="#" >Features</a></li>
							<li><a href="#">Pricing</a></li>
							<li><a href="#">FAQs</a></li>
							<li><a href="#">About</a></li>

						</ul>
					</div>
				</div>
			</footer>
		</div>
		
		<div class="voltartopo">
			<a href="#topo" class="btn btn-light">Voltar ao topo</a>
		</div>
		<p>&copy; 2023 Company, Inc. All rights reserved.</p>
	</section>
    <script>
        document.querySelectorAll('a').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                window.scrollTo({
                    top: targetElement.offsetTop - document.querySelector('header').offsetHeight,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
