<style>
        /* Full-width navbar with transparent background and fixed height */
        .navbar {
            width: 100%;
            height: 100px;
            padding-left: 0px;
            padding-right: 0px;
            box-sizing: border-box;
            background-color: #333333;
            position: fixed;
            top: 0;
            z-index: 1080;
        }

        /* Centered logo */
        .navbar-brand {
            position: absolute;
            left: 50%;
            top: 0px;
            transform: translateX(-50%);
            height: 100%;
            display: flex;
            align-items: center;
        }

        /* Toggle button on the right */
        .navbar-toggler {
            float: left;
            color: #fff;
            padding: .15rem .35rem;
            font-size: 20px;
            border: 2px solid rgb(139 139 139) !important;
        }

        /* Side menu styling */
        .side-menu {
            position: fixed;
            top: 0;
            right: -40%;
            width: 17%;
            height: 100%;
            background-color: #fff;
            transition: right 0.3s ease-in-out;
            z-index: 1090;
            padding: 1rem;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* When the menu is open */
        .side-menu.open {
            animation: slideIn 0.3s forwards;
            left: 0;
        }

        @keyframes slideIn {
            from {
                right: -40%;
            }
            to {
                right: 0;
            }
        }

        /* Overlay background */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1030;
        }

        .overlay.show {
            display: block;
        }

        /* Close button styling */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 30px;
            color: #333;
            cursor: pointer;
        }

        /* Navigation item styling */
        .side-menu .nav-link {
            font-size: 18px;
            color: #333;
            margin-bottom: 0;
            text-align: left;
            padding-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .bold {
            font-weight: bold;
        }

        .main_cat_li li a {
            font-size: 16px;
            line-height: 16px;
        }

        .main_cat_li li a:hover {
            font-weight: bold;
        }

        .full-width-container {
            width: 100%;
            padding-left: 20px;
            padding-right: 20px;
            box-sizing: border-box;
        }

         @media (max-width: 768px) {
            .navbar-brand {
                left: 60%;
            }
            .navbar-toggler {
              margin-left:8px;
            }
            .side-menu {
            width: 40%;
                
            }
        }        
    </style>

    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container"  style="padding:0px !important;">
            <!-- Centered Logo -->
            <a class="navbar-brand" href="index.php">
                 <span style="line-height: 22px;font-size: 20px;font-weight: 600;font-weight: 900;font-family: arial;">CORRECTION <br/>TERRITORY <br/><i style="font-size: 14px;font-weight: 200;">invest simply</i></span>
                <img src="images/logo-bear.png"
                    alt="Logo" width="150">
            </a>
            <!-- Toggle button on the right -->
            <button class="navbar-toggler" type="button" id="menuToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- White side menu -->
    <div class="side-menu" id="sideMenu">
        <!-- Close button -->
        <span class="close-btn" id="closeMenu">&times;</span>

        <!-- Menu items -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link bold" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link bold" href="about.php">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link bold" href="contact-us.php">Contact Us</a>
            </li>
        </ul>
    </div>

    <!-- Overlay behind the menu -->
    <div class="overlay" id="overlay"></div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle side menu and overlay
        document.getElementById('menuToggle').addEventListener('click', function () {
            var sideMenu = document.getElementById('sideMenu');
            var overlay = document.getElementById('overlay');

            // Toggle menu visibility
            sideMenu.classList.toggle('open');

            // Show/hide overlay
            overlay.classList.toggle('show');
        });

        // Hide the side menu and overlay when clicking on the overlay
        document.getElementById('overlay').addEventListener('click', function () {
            document.getElementById('sideMenu').classList.remove('open');
            this.classList.remove('show');
        });

        // Close the menu when the close button is clicked
        document.getElementById('closeMenu').addEventListener('click', function () {
            document.getElementById('sideMenu').classList.remove('open');
            document.getElementById('overlay').classList.remove('show');
        });
    </script>  

