<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <!-- Boxicons CSS -->
    <link href="{{ asset('css/boxicons.min.css') }}" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="https://unpkg.com/boxicons/css/boxicons.min.css"> -->
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Optionally, you can include FontAwesome for icons -->
    <link href="{{ asset('css/bootstrap-4.5.2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-5.3.3.min.css') }}" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> 
    <title>Inventory Management System</title>
 
    <x-app-style />
  </head>
  <body>
  
  <x-sidebar />
  <x-navbar />
  <br><br><br><br>
 
  
  <div class="app-container">
    
    <div style="margin-left:20%;padding:1px 16px;height:1000px;">
      <!-- sidebar -->
      <main>
        {{ $slot }}
      </main>
    </div>
    
  </div>

  <footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
      <span class="text-muted">&copy; 2024 Inventory Management System. All rights reserved.</span>
      <p class="mb-0">Designed by IT Team</p>
    </div>
  </footer>

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    let warningTimer;
    let logoutTimer;
    
    const warningTime = 59 * 60 * 1000; 
    const logoutTime = 60 * 60 * 1000; 
    
    function startTimers() {
        warningTimer = setTimeout(showWarning, warningTime);
        logoutTimer = setTimeout(autoLogout, logoutTime);
    }

    function resetTimers() {
        clearTimeout(warningTimer);
        clearTimeout(logoutTimer);
        startTimers();
    }

    function showWarning() {
        alert("You will be logged out in 1 minute due to inactivity.");
    }

    function autoLogout() {
        // Create a form dynamically
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("logout") }}';
        
        // Add CSRF token input
        let csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Append the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    }

    document.onload = startTimers();
    document.onmousemove = resetTimers;
    document.onkeypress = resetTimers;
    document.addEventListener('DOMContentLoaded', () => {
      const body = document.querySelector('body');
      const darkLight = document.querySelector('#darkLight');
      const sidebar = document.querySelector('.sidebar');
      const submenuItems = document.querySelectorAll('.submenu_item');
      const sidebarOpen = document.querySelector('#sidebarOpen');
      const sidebarClose = document.querySelector('.collapse_sidebar');
      const sidebarExpand = document.querySelector('.expand_sidebar');

      sidebarOpen?.addEventListener('click', () => {
        sidebar.classList.toggle('close');
      });

      sidebarClose.addEventListener('click', () => {
        sidebar.classList.add('close', 'hoverable');
      });

      sidebarExpand.addEventListener('click', () => {
        sidebar.classList.remove('close', 'hoverable');
      });

      sidebar.addEventListener('mouseenter', () => {
        if (sidebar.classList.contains('hoverable')) {
          sidebar.classList.remove('close');
        }
      });

      sidebar.addEventListener('mouseleave', () => {
        if (sidebar.classList.contains('hoverable')) {
          sidebar.classList.add('close');
        }
      });

      darkLight.addEventListener('click', () => {
        body.classList.toggle('dark');
        if (body.classList.contains('dark')) {
          darkLight.classList.replace('bx-sun', 'bx-moon');
        } else {
          darkLight.classList.replace('bx-moon', 'bx-sun');
        }
      });

      submenuItems.forEach((item, index) => {
        item.addEventListener('click', () => {
          item.classList.toggle('show_submenu');
          submenuItems.forEach((item2, index2) => {
            if (index !== index2) {
              item2.classList.remove('show_submenu');
            }
          });
        });
      });

      if (window.innerWidth < 768) {
        sidebar.classList.add('close');
      } else {
        sidebar.classList.remove('close');
      }
    });
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            let menu = this.nextElementSibling;
            menu.classList.toggle('show');
        });
    });
  </script>
    
  </body>
</html>
