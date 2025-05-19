<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parking System</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-200">

    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-blue-700 text-lg font-semibold">Home</a>

        <div class="flex items-center space-x-6">
            <div data-auth-only style="display: none;" class="flex items-center space-x-4">
                
                {{-- for admin Features --}}
                <div id="admin-features" class="flex space-x-4" style="display: none;">
                    <a href="{{ route('area') }}"
                    class="text-blue-700 {{ request()->routeIs('area') ? 'underline font-semibold' : '' }}">
                    Area
                    </a>

                    
                    <a href="{{ route('parking-rate') }}"
                    class="text-blue-700 {{ request()->routeIs('parking-rate') ? 'underline font-semibold' : '' }}">
                    Parking Rate
                    </a>
                
                    <a href="{{ route('users') }}"
                    class="text-blue-700 {{ request()->routeIs('users') ? 'underline font-semibold' : '' }}">
                    Users
                    </a>

                    {{-- <a href="{{ route('exception-rate') }}"
                    class="text-blue-700 {{ request()->routeIs('exception-rate') ? 'underline font-semibold' : '' }}">
                    Exception Rate
                    </a> --}}
                </div>
                
                <span id="welcomeMessage" class="text-gray-700 font-medium"></span>
                <button data-logout-button class="text-red-700">Logout</button>
            </div>

            <div data-guest-only style="display: none;">
                <span class="text-gray-600">Hi, you need to sign in to proceed.</span>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-4">
        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('authToken');
            const userData = localStorage.getItem('authUser');

            document.querySelectorAll('[data-auth-only]').forEach(el => {
                el.style.display = token ? 'flex' : 'none';
            });

            document.querySelectorAll('[data-guest-only]').forEach(el => {
                el.style.display = token ? 'none' : 'block';
            });

            if (userData) {
                const user = JSON.parse(userData);
                const welcomeMessage = document.getElementById('welcomeMessage');
                welcomeMessage.textContent = `Hi, ${user.name}`;

                if (user.role.role === 'Admin' || user.role.role === 'Superadmin') {
                    const adminSection = document.getElementById('admin-features');
                    if (adminSection) {
                        adminSection.style.display = 'flex';
                    }
                }
            }

        document.querySelectorAll('[data-logout-button]').forEach(btn => {
            btn.addEventListener('click', async function () {
                const query = `
                mutation LogoutUser {
                    logoutUser {
                        message
                        status
                    }
                }
                `;

                try {
                    const res = await fetch('/graphql', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                        },
                        body: JSON.stringify({ query })
                    });

                    const data = await res.json();
                    if (data.errors) {
                        console.error(data.errors[0].message);
                        alert('Logout failed: ' + data.errors[0].message);
                    } else {
                        localStorage.removeItem('authToken');
                        localStorage.removeItem('authUser');
                        window.location.href = '/';
                    }
                } catch (error) {
                    console.error('Logout error:', error);
                }
            });
        });        
    });
    </script>
</body>
</html>
