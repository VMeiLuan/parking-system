@extends('layout.app')

@section('content')
<div class="w-full space-y-8">
    <h1 class="text-xl font-semibold mb-4">List of users</h1>

    <div>
        <table class="table-fixed w-full bg-white shadow rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="w-1/2 px-4 py-2 text-left">Name</th>
                    <th class="w-1/4 px-4 py-2 text-left">Email</th>
                    <th class="w-1/4 px-4 py-2 text-left">Created</th>
                    <th class="w-1/4 px-4 py-2 text-left">Updated</th>
                    <th class="w-1/4 px-4 py-2 text-left">Role</th>
                </tr>

            <tbody id="list-of-users">
                <tr>
                    <td colspan="5" class="text-center px-4 py-2 text-gray-500">Loading users...</td>
                </tr>
            </tbody>        
        </table>
    </div>

</div>

<script>
    const token = localStorage.getItem('authToken');
    if (!token) {
        // Redirect authenticated users to home or dashboard
        window.location.href = '/'; // or '/'
    }

    document.addEventListener('DOMContentLoaded', async function () {
        const tbody = document.getElementById('list-of-users');

        const query = `
            query {
                users {
                    id
                    name
                    email
                    created_at
                    updated_at
                    role {
                        role
                    }
                }
            }
        `;

        try {
            const res = await fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // Include auth if needed
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                body: JSON.stringify({ query })
            });

            const result = await res.json();
            console.log(result)
            const rates = result.data?.users || [];
            if (rates.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center px-4 py-2 text-gray-500">No user at this moment.</td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';

            rates.forEach((rate, index) => {
                const rowClass = index % 2 === 1 ? 'bg-gray-50' : '';
                const row = `
                    <tr class="${rowClass}">
                        <td class="border px-4 py-2">${rate.name || '-'}</td>
                        <td class="border px-4 py-2">${rate.email || '-'}</td>
                        <td class="border px-4 py-2">${rate.created_at || '-'}</td>
                        <td class="border px-4 py-2">${rate.updated_at || '-'}</td>
                        <td class="border px-4 py-2">${rate.role.role || '-'}</td>
                    </tr>                
                    `;
                tbody.insertAdjacentHTML('beforeend', row);
            });

        } catch (error) {
            console.error('Error loading rates:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center px-4 py-2 text-red-500">Failed to load rates.</td>
                </tr>
            `;
        }
    });
</script>
@endsection