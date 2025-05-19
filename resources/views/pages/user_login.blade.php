@extends('layout.app')

@section('content')
<div class="w-full">
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" id="loginForm">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                Email
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="text" placeholder="Email" name="email">
            </div>

            <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                Password
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************">
            </div>

            <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Sign In
            </button>

            <a href="{{ route("home") }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" >Back</a>

        </div>
    </form>
</div>

<p id="responseMessage" class="text-green-600 font-semibold mt-4"></p>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const token = localStorage.getItem('authToken');
        if (token) {
            // Redirect authenticated users to home or dashboard
            window.location.href = '/'; // or '/'
        }
    });

    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault(); 

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const responseElement = document.getElementById('responseMessage');

        const query = `
        mutation LoginUser($email: String!, $password: String!) {
            loginUser(email: $email, password: $password) {
            user {
                id
                email
                name
                role {
                    role
                }
            }
            message
            token
            }
        }
        `;

        const variables = {
            email: email,
            password: password,
        };

        try {
            const res = await fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ query, variables })
            });

            const data = await res.json();

            if (data.errors) {
                responseElement.textContent = data.errors[0].message;
                responseElement.classList.replace('text-green-600', 'text-red-600');
                console.log(data.errors)
            } else {
                const result = data.data.loginUser;
                responseElement.textContent = result.message;
                responseElement.classList.replace('text-red-600', 'text-green-600');

                if (result.token) {
                        localStorage.setItem('authToken', result.token);
                        localStorage.setItem('authUser', JSON.stringify(result.user));
                        
                        if(result.user.role.role == 'Superadmin' || result.user.role.role == 'Admin' ) {
                            window.location.href = '/admin/area-setting';
                        } else {
                            window.location.href = '/parking-system';
                        }
                    }
            }
        } catch (error) {
            responseElement.textContent = "Something went wrong.";
            responseElement.classList.replace('text-green-600', 'text-red-600');
        }
    });
</script>

@endsection
