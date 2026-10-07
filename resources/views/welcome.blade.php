<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Nodo Federal</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/ICONO_LF.ico" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- =======================================================
    * Template Name: Arsha
    * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
    * Updated: Feb 22 2025 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <img src="assets/img/ICONO_LF.ico" alt="Nodo Federal Logo" style="height: 50px; margin-right: 10px; border-radius: 50%;">

            <a href="#" class="logo d-flex align-items-center me-auto">
                <h1 class="sitename"> Nodo Federal</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Inicio</a></li>
                    <li><a href="#about">Nosotros</a></li>
                    <li><a href="#contact">Contacto</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="{{ route('login') }}">Ingresar</a>

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background"
            style="background-image: linear-gradient(rgba(15, 30, 48, 0.72), rgba(15, 30, 48, 0.72)), url('{{ asset('assets/img/CCSF-vieja.jpg') }}'); background-position: center; background-size: cover; background-repeat: no-repeat;">

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center"
                        data-aos="zoom-out">
                        <h1>Más de 50 años brindando Servicios de Informes Comerciales y Financieros.</h1>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
                        <img src="assets/img/NF-LOGO.jpg" class="img-fluid animated rounded-5" alt="">
                    </div>
                </div>
            </div>

        </section>
        <!-- /Hero Section -->

        <!-- Clients Section -->
        <section id="clients" class="clients section light-background">

            <div class="container" data-aos="zoom-in">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
            {
                "loop": true,
                "speed": 600,
                "autoplay": {
                  "delay": 5000
                },
                "slidesPerView": "auto",
                "pagination": {
                  "el": ".swiper-pagination",
                  "type": "bullets",
                  "clickable": true
                },
                "breakpoints": {
                  "320": {
                      "slidesPerView": 2,
                      "spaceBetween": 40
                    },
                    "480": {
                      "slidesPerView": 3,
                      "spaceBetween": 60
                    },
                    "640": {
                      "slidesPerView": 4,
                      "spaceBetween": 80
                    },
                    "992": {
                      "slidesPerView": 5,
                      "spaceBetween": 120
                    },
                    "1200": {
                      "slidesPerView": 6,
                      "spaceBetween": 120
                    }
                }
            }
        </script>
            <div class="swiper-wrapper align-items-center">
                <div class="swiper-slide">
                    <a href="https://servicioswww.anses.gob.ar/C2-ConstaCUIL" aria-label="ANSES" class="d-flex align-items-center gap-3 text-decoration-none text-dark" target="_blank" rel="noopener noreferrer">
                        <img src="assets/img/anses_logo.png" class="img-fluid" alt="ANSES" title="Consultar constancia de CUIL en ANSES">
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="https://www.santafe.gob.ar/boletinoficial/" aria-label="Boletín Oficial Santa Fe" class="d-flex align-items-center gap-3 text-decoration-none text-dark" target="_blank" rel="noopener noreferrer">
                        <img src="assets/img/santa-fe.png" class="img-fluid" alt="Santa Fe" title="Consultar Boletín Oficial de Santa Fe">
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="https://www.boletinoficial.gob.ar/" aria-label="Boletín Oficial Argentina" class="d-flex align-items-center gap-3 text-decoration-none text-dark" target="_blank" rel="noopener noreferrer">
                        <img src="assets/img/logo-boletin.png" class="img-fluid" alt="Presidencia" title="Consultar Boletín Oficial de Argentina">
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="https://www.afip.gob.ar/landing/default.asp" aria-label="ARCA" class="d-flex align-items-center gap-3 text-decoration-none text-dark" target="_blank" rel="noopener noreferrer">
                        <img src="assets/img/arca.png" class="img-fluid" alt="Arca" title="Consultar información en ARCA">
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="https://www.santafe.gob.ar/index.php/web/Temas-Nuevo-Portal/Catastro/" aria-label="Catastro" class="d-flex align-items-center gap-3 text-decoration-none text-dark" target="_blank" rel="noopener noreferrer">
                        <img src="assets/img/catastro.png" class="img-fluid" alt="Catastro" title="Consultar información de catastro">
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="https://www.santafe.gov.ar/index.php/web/content/view/full/102282" aria-label="API Santa Fe" class="d-flex align-items-center gap-3 text-decoration-none text-dark" target="_blank" rel="noopener noreferrer">
                        <img src="assets/img/api.png" class="img-fluid" alt="Santa Fe" title="Consultar API de Santa Fe">
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="https://www.santafe.gov.ar/gestionesciudadanas" aria-label="Gestión Ciudadana Santa Fe" class="d-flex align-items-center gap-3 text-decoration-none text-dark" target="_blank" rel="noopener noreferrer">
                        <img src="assets/img/timbo.png" class="img-fluid" alt="Santa Fe" title="Gestión Ciudadana Santa Fe">
                    </a>
                </div>
            </div>
        </div>
    </div>
        </section><!-- /Clients Section -->

        <!-- About Section -->
        <section id="about" class="about section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Acerca de Nosotros</h2>
            </div><!-- End Section Title -->
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
                        <h2>Presentación del Servicio</h2>
                        <p> Obtenga en segundos y en cualquier momento la información estratégica necesaria para
                            evaluar solicitudes y minimizar riesgos comerciales y financieros. Con solo ingresar el
                            DNI, nuestra plataforma le permite acceder a reportes detallados, consolidados y
                            actualizados sobre la situación laboral, patrimonial y crediticia de sus clientes o
                            asociados.
                        </p>
                        <h2>Plataforma Digital e Intuitiva</h2>
                        <p> Los profesionales y asociados a entidades adheridas (como la Caja de Seguridad
                            Social de Abogados y Procuradores, el Colegio de Martilleros y Corredores
                            Públicos de Santa Fe y el Colegio de Corredores Inmobiliarios) pueden tramitar
                            su credencial individual de usuario y contraseña.
                        </p>
                        <h2>Acceso exclusivo para Afiliados</h2>
                        <p> Los profesionales y asociados a entidades adheridas (como la Caja de Seguridad
                            Social de Abogados y Procuradores, el Colegio de Martilleros y Corredores
                            Públicos de Santa Fe y el Colegio de Corredores Inmobiliarios) pueden tramitar
                            su credencial individual de usuario y contraseña.
                        </p>
                    </div>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <h2>Esquema Operativo</h2>
                        <h4>Datos Generales</h4>
                        <p> Datos personales completos y de contacto.</p>
                        <h4>Historial Financiero</h4>
                        <p> Reportes consolidados del Banco Central de la RepúblicaArgentina (BCRA).</p>
                        <h4>Situación Fiscal y Laboral</h4>
                        <p> Antecedentes laborales y categoría de Monotributo.</p>
                        <h4>Evaluación de Riesgo</h4>
                        <p> Estado de morosidad, informes de Veraz y score predictivo de ingresos.</p>
                        <h4>Patrimonio</h4>
                        <p> Consulta de bienes registrados.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- /About Section -->


        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Contacto</h2>
            </div>
            <!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-5">

                        <div class="info-wrap">
                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                                <i class="bi bi-geo-alt flex-shrink-0"></i>
                                <div>
                                    <h3>Dirección</h3>
                                    <p>Irigoyen Freyre 2650, Santa Fe, Argentina</p>
                                </div>
                            </div>
                            <!-- End Info Item -->

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                                <i class="bi bi-telephone flex-shrink-0"></i>
                                <div>
                                    <h3>Teléfono</h3>
                                    <p>+54 9 3426 26-7364</p>
                                </div>
                            </div>
                            <!-- End Info Item -->

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                                <i class="bi bi-envelope flex-shrink-0"></i>
                                <div>
                                    <h3>Email</h3>
                                    <p>soportenodof@gmail.com</p>
                                </div>
                            </div>
                            <!-- End Info Item -->

                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3396.6619011146017!2d-60.70886762364475!3d-31.64310630724953!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95b5a9bb078f3083%3A0xbe2bf81cf053c423!2sIrigoyen%20Freyre%202650%2C%20S3000BPI%20S3000BPI%2C%20Santa%20Fe!5e0!3m2!1ses!2sar!4v1791224004584!5m2!1ses!2sar" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin">
                            </iframe>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <form action="{{ route('contact.store') }}" method="post" class="php-email-form" data-aos="fade-up"
                            data-aos-delay="200">
                            @csrf
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <label for="name-field" class="pb-2">Apellido y Nombres</label>
                                    <input type="text" name="name" id="name-field" class="form-control"
                                        required="">
                                </div>

                                <div class="col-md-6">
                                    <label for="email-field" class="pb-2">Correo Electrónico</label>
                                    <input type="email" class="form-control" name="email" id="email-field"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <label for="subject-field" class="pb-2">Asunto</label>
                                    <input type="text" class="form-control" name="subject" id="subject-field"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <label for="message-field" class="pb-2">Mensaje</label>
                                    <textarea class="form-control" name="message" rows="10" id="message-field" required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Cargando</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Tu mensaje ha sido enviado. ¡Gracias!</div>
                                    <button type="submit">Enviar Mensaje</button>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- End Contact Form -->
                </div>
            </div>
        </section>
        <!-- /Contact Section -->

    </main>

    <footer id="footer" class="footer">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="d-flex align-items-center">
                        <span class="sitename">Nodo Federal</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>Irigoyen Freyre 2650</p>
                        <p>Santa Fe, Argentina</p>
                        <p class="mt-3"><strong>Teléfono:</strong> <span>+54 9 3426 26-7364</span></p>
                        <p><strong>Email:</strong> <span>soportenodof@gmail.com</span></p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Enlaces Útiles</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#hero">Inicio</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#about">Sobre Nosotros</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright 2026</span> <strong class="px-1 sitename">Nodo Federal</strong> <span>Todos los
                    Derechos Reservados</span></p>
            <div class="credits">
                Desarrollado por <a href="https://nodofederal.com.ar/">OM Computación</a>
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>
