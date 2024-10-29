<style>
      /* Import Google font - Poppins */
            @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: "nexa";
                font-size: 16px;
                }
                :root {
                --white-color: #fff;
                --blue-color: #4070f4;
                --grey-color: #707070;
                --grey-color-light: #aaa;
                }
                body {
                background-color: #e7f2fd;
                transition: all 0.5s ease;
                }
                body.dark {
                background-color: #333;
                }
                body.dark {
                --white-color: #333;
                --blue-color: #fff;
                --grey-color: #f2f2f2;
                --grey-color-light: #aaa;
                }
                .app-container {
                    display: flex;
                    flex-direction: column;
                    padding: 10px;
                }

                /* navbar */
                .navbar {
                position: fixed;
                top: 0;
                width: 100%;
                left: 0;
                background-color: var(--white-color);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 15px 30px;
                z-index: 1000;
                box-shadow: 0 0 2px var(--grey-color-light);
                }
                .logo_item {
                display: flex;
                align-items: center;
                column-gap: 10px;
                font-size: 22px;
                font-weight: 500;
                color: var(--blue-color);
                }
                .navbar img {
                width: 35px;
                height: 35px;
                border-radius: 50%;
                }
                .search_bar {
                height: 47px;
                max-width: 430px;
                width: 100%;
                }
                .search_bar input {
                height: 100%;
                width: 100%;
                border-radius: 25px;
                font-size: 18px;
                outline: none;
                background-color: var(--white-color);
                color: var(--grey-color);
                border: 1px solid var(--grey-color-light);
                padding: 0 20px;
                }
                .navbar_content {
                display: flex;
                align-items: center;
                column-gap: 25px;
                }
                .navbar_content i {
                cursor: pointer;
                font-size: 20px;
                color: var(--grey-color);
                }

                /* sidebar */
                .sidebar {
                background-color: var(--white-color);
                width: 290px;
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                padding: 80px 20px;
                z-index: 100;
                overflow-y: scroll;
                box-shadow: 0 0 1px var(--grey-color-light);
                transition: all 0.5s ease;
                }
                .sidebar.close {
                padding: 60px 0;
                width: 80px;
                }
                .sidebar::-webkit-scrollbar {
                display: none;
                }
                .menu_content {
                position: relative;
                }
                .menu_title {
                margin: 15px 0;
                padding: 0 20px;
                font-size: 18px;
                }
                .sidebar.close .menu_title {
                padding: 6px 30px;
                }
                .menu_title::before {
                color: var(--grey-color);
                white-space: nowrap;
                }
                .menu_dahsboard::before {
                content: "Dashboard";
                }
                .menu_editor::before {
                content: "Editor";
                }
                .menu_setting::before {
                content: "Setting";
                }
                .sidebar.close .menu_title::before {
                content: "";
                position: absolute;
                height: 2px;
                width: 18px;
                border-radius: 12px;
                background: var(--grey-color-light);
                }
                .menu_items {
                padding: 0;
                list-style: none;
                }
                .navlink_icon {
                position: relative;
                font-size: 22px;
                min-width: 50px;
                line-height: 40px;
                display: inline-block;
                text-align: center;
                border-radius: 6px;
                }
                .navlink_icon::before {
                content: "";
                position: absolute;
                height: 100%;
                width: calc(100% + 100px);
                left: -20px;
                }
                .navlink_icon:hover {
                background: var(--blue-color);
                }
                .sidebar .nav_link {
                display: flex;
                align-items: center;
                width: 100%;
                padding: 4px 15px;
                border-radius: 8px;
                text-decoration: none;
                color: var(--grey-color);
                white-space: nowrap;
                }
                .sidebar.close .navlink {
                display: none;
                }
                .nav_link:hover {
                color: var(--white-color);
                background: var(--blue-color);
                }
                .sidebar.close .nav_link:hover {
                background: var(--white-color);
                }
                .submenu_item {
                cursor: pointer;
                }
                .submenu {
                display: none;
                }
                .submenu_item .arrow-left {
                position: absolute;
                right: 10px;
                display: inline-block;
                margin-right: auto;
                }
                .sidebar.close .submenu {
                display: none;
                }
                .show_submenu ~ .submenu {
                display: block;
                }
                .show_submenu .arrow-left {
                transform: rotate(90deg);
                }
                .submenu .sublink {
                padding: 15px 15px 15px 52px;
                }
                .bottom_content {
                position: fixed;
                bottom: 60px;
                left: 0;
                width: 260px;
                cursor: pointer;
                transition: all 0.5s ease;
                }
                .bottom {
                position: absolute;
                display: flex;
                align-items: center;
                left: 0;
                justify-content: space-around;
                padding: 18px 0;
                text-align: center;
                width: 100%;
                color: var(--grey-color);
                border-top: 1px solid var(--grey-color-light);
                background-color: var(--white-color);
                }
                .bottom i {
                font-size: 20px;
                }
                .bottom span {
                font-size: 18px;
                }
                .sidebar.close .bottom_content {
                width: 50px;
                left: 15px;
                }
                .sidebar.close .bottom span {
                display: none;
                }
                .sidebar.hoverable .collapse_sidebar {
                display: none;
                }
                #sidebarOpen {
                display: none;
                }
                @media screen and (max-width: 768px) {
                #sidebarOpen {
                    font-size: 25px;
                    display: block;
                    margin-right: 10px;
                    cursor: pointer;
                    color: var(--grey-color);
                }
                .sidebar.close {
                    left: -100%;
                }
                .search_bar {
                    display: none;
                }
                .sidebar.close .bottom_content {
                    left: -100%;
                }
            }

            /* .footer {
                background: linear-gradient(-135deg, #71b7e6, #9b59b6);
                color: #fff;
                text-align: center;
                padding: 10px;
            } */

            /* .footer {
                background: linear-gradient(-135deg, #71b7e6, #9b59b6);
                color: #fff;
                text-align: center;
                padding: 10px;
            } */

            .metrics, .alerts {
                margin-bottom: 20px;
            }

            .metrics {
                display: flex;
                justify-content: space-between;
            }

            .metrics .card {
                flex: 1;
                margin: 0 10px;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            

            .alerts {
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .alert {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
                padding: 10px;
                background: #f8d7da;
                border-left: 4px solid #f5c2c7;
                border-radius: 4px;
            }

            .alert span {
                margin-right: 10px;
            }

            .alert-info {
                background: #d1ecf1;
                border-left-color: #bee5eb;
            }

            .alert-warning {
                background: #fff3cd;
                border-left-color: #ffeeba;
            }

            .alert-success {
                background: #d4edda;
                border-left-color: #c3e6cb;
            } 
            
            .body {
                padding-top: 70px; /* Adjust according to your navbar height */
                padding-bottom: 100px; /* Space for the footer */
            }
            .app-container {
                min-height: calc(100vh - 170px); /* Adjust to fit the main content area */
            }
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                background-color: #f8f9fa; /* Bootstrap default light background color */
            }
            .card {
                text-decoration: none;
                color: inherit;
            }

            .card:hover {
                text-decoration: none;
                color: inherit;
            }

            .hidden {
                display: none;
            }

            .visible {
                display: block;
            }
            .filter-form {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 10px;
                align-items: end;
                margin-bottom: 20px;
            }
            .filter-form div {
                display: flex;
                flex-direction: column;
            }
            .filter-form button {
                height: 100%;
            }
            .report-actions {
                margin-bottom: 20px;
            }
            .report-actions a {
                margin-right: 10px;
            }
            .logo {
                text-align: left;
                margin-bottom: 20px;
            }
            .statements {
                text-align: left;
                margin-top: 20px;
            }
            @media print {
                header, footer {
                    position: fixed;
                    width: 100%;
                }
                header {
                    top: 0;
                }
                footer {
                    bottom: 0;
                }
                .page-number::after {
                    content: counter(page);
                }
            }
            .print-adjust {
                margin: 0;
                padding: 0;
                width: 100%;
                position: absolute;
                left: 0;
            }
            .active {
                font-weight: bold;
                color: #007bff;
            }
            @media (max-width: 768px) {
                .custom-class {
                    font-size: 14px;
                }
            }
            /* CSS for mobile responsiveness */
            @media (max-width: 768px) {
                .sidebar {
                    display: none; /* Hide sidebar on small screens */
                }
                
                .app-container {
                    margin-left: 0;
                    padding: 0 15px;
                }
                
                .footer {
                    font-size: 12px;
                    padding: 10px;
                }
                
                .navbar {
                    padding: 10px;
                }
            }
            /* Responsive grid */
            @media (max-width: 768px) {
                .app-container {
                    display: flex;
                    flex-direction: column;
                    padding: 10px;
                }
            }


            
    </style>