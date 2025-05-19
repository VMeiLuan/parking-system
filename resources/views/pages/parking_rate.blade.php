@extends('layout.app')

@section('content')
<div class="w-full space-y-8">

    {{-- Add new parking rate --}}
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
        <h1 class="text-xl font-semibold mb-4">Fill in a new rate below</h1>

        <form id="createParkingRate" class="space-y-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-1">Spcecial rate
                </label>
                <input type="checkbox">

                {{-- date here is special rate --}}
                <br><br>


                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-1">
                    * Title
                </label>
                <input id="title" name="title" type="text" placeholder="Title"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-2">
                    * Hours
                </label>
                <input id="hours" name="hours" type="text" placeholder="Hours"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-3">
                    * Fees (per Hour in MYR)
                </label>
                <input id="fees" name="fees" type="text" placeholder="Fees (per Hour)"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-3">
                    * Description
                </label>
                <input id="description" name="description" type="text" placeholder="Description"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-3">
                    Remark
                </label>
                <input id="remark" name="remark" type="text" placeholder="Remark"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex items-center">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create
                </button>
            </div>
        </form>
    </div>
    <p id="responseMessage" class="text-green-600 font-semibold mt-4"></p>

    {{-- Current rate section --}}
    <div>
        <h1 class="text-xl font-semibold mb-4">Current rate</h1>

        <table class="table-fixed w-full bg-white shadow rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="w-1/2 px-4 py-2 text-left">Title</th>
                    <th class="w-1/4 px-4 py-2 text-left">Hour</th>
                    <th class="w-1/4 px-4 py-2 text-left">Fees (per Hour)</th>
                    <th class="w-1/4 px-4 py-2 text-left">Description</th>
                    <th class="w-1/4 px-4 py-2 text-left">Remark</th>
                    <th class="w-1/4 px-4 py-2 text-left"></th>
                    <th class="w-1/4 px-4 py-2 text-left"></th>
                </tr>

            <tbody id="list-of-rate">
                <tr>
                    <td colspan="5" class="text-center px-4 py-2 text-gray-500">Loading rates...</td>
                </tr>
            </tbody>        
        </table>
    </div>
</div>
<br>

{{-- hidden edit section per id --}}
<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
    <div class="bg-white w-full max-w-md p-6 rounded shadow-lg">
        <h2 class="text-xl font-bold mb-4">Edit Parking Rate</h2>
        <form id="editParkingRateFormn">
            <input type="hidden" id="edit-id" />
            <div class="mb-4">
                <label for="edit-title" class="block text-sm font-medium text-gray-700">Title</label>
                <input id="edit-title" name="title" type="text" class="mt-1 block w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label for="edit-hours" class="block text-sm font-medium text-gray-700">Hours</label>
                <input id="edit-hours" name="hours" type="text" class="mt-1 block w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label for="edit-fees" class="block text-sm font-medium text-gray-700">Fees</label>
                <input id="edit-fees" name="fees" type="text" class="mt-1 block w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label for="edit-description" class="block text-sm font-medium text-gray-700">Description</label>
                <input id="edit-description" name="description" type="text" class="mt-1 block w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label for="edit-remark" class="block text-sm font-medium text-gray-700">Remark</label>
                <input id="edit-remark" name="remark" type="text" class="mt-1 block w-full border px-3 py-2 rounded">
            </div>
            <div class="flex justify-between">
                <button type="button" id="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded" style="cursor:pointer">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded" style="cursor:pointer">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        const token = localStorage.getItem('authToken');
        if (!token) {
            // Redirect authenticated users to home or dashboard
            window.location.href = '/'; // or '/'
        }

        // to display list of rate
        const tbody = document.getElementById('list-of-rate');

        const query = `
            query {
                parkingrates {
                    id
                    title
                    hours
                    fees
                    description
                    remark
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
            const rates = result.data?.parkingrates || [];
            if (rates.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center px-4 py-2 text-gray-500">No rate available now.</td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';

            rates.forEach((rate, index) => {
                const rowClass = index % 2 === 1 ? 'bg-gray-50' : '';
                const row = `
                    <tr class="${rowClass}">
                        <td class="border px-4 py-2">${rate.title || '-'}</td>
                        <td class="border px-4 py-2">${rate.hours || '-'}</td>
                        <td class="border px-4 py-2">${rate.fees || '-'}</td>
                        <td class="border px-4 py-2">${rate.description || '-'}</td>
                        <td class="border px-4 py-2">${rate.remark || '-'}</td>
                        <td class="border px-4 py-2">
                            <button 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline edit-btn" style="cursor:pointer"
                                data-id="${rate.id}"
                            >
                                Edit
                            </button>
                        </td>
                        <td class="border px-4 py-2">
                            <button 
                                class="delete-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline dlt-btn"
                                data-id="${rate.id}"
                                style="cursor:pointer"
                            >
                                Delete
                            </button>
                        </td>                    
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
    // to display list of rate

    // to create a new record
    document.getElementById('createParkingRate').addEventListener('submit', async function(e) {
        e.preventDefault(); 

        const title = document.getElementById('title').value;
        const hours = document.getElementById('hours').value;
        const fees = document.getElementById('fees').value;
        const description = document.getElementById('description').value;
        const remark = document.getElementById('remark').value;
        const responseElement = document.getElementById('responseMessage');

        const query = `
        mutation CreateParkingRate($title: String!, $hours: String!, $fees: String!, $description: String!, $remark: String) {
            createParkingRate(title: $title, hours: $hours, fees: $fees, description: $description, remark: $remark) {
                parkingrate {
                    id
                    title
                    hours
                    fees
                    description
                    remark
                }
                message
            }
        }
        `;

        const variables = {
            title: title,
            hours: hours,
            fees: fees,
            description: description,
            remark: remark,
        };
        console.log(localStorage.getItem('authToken'))
        try {
            const res = await fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                body: JSON.stringify({ query, variables })
            });

            const data = await res.json();
            if (data.errors) {
                responseElement.textContent = data.errors[0].message;
                responseElement.classList.replace('text-red-600', 'text-red-600');
            } else {
                const result = data.data.createParkingRate;
                responseElement.textContent = result.message;
                responseElement.classList.replace('text-red-600', 'text-green-600');
                location.reload();
            }
        } catch (error) {
            responseElement.textContent = "Something went wrong.";
            responseElement.classList.replace('text-red-600', 'text-red-600');
        }
    });
    // to create a new record

    document.getElementById('list-of-rate').addEventListener('click', async function (e) {
        if (e.target.classList.contains('edit-btn')) {
            const rateId = e.target.dataset.id;

            const row = e.target.closest('tr');
            const cells = row.querySelectorAll('td');

            document.getElementById('edit-id').value = rateId;
            document.getElementById('edit-title').value = cells[0].innerText;
            document.getElementById('edit-hours').value = cells[1].innerText;
            document.getElementById('edit-fees').value = cells[2].innerText;
            document.getElementById('edit-description').value = cells[3].innerText;
            document.getElementById('edit-remark').value = cells[4].innerText;

            document.getElementById('editModal').classList.remove('hidden');
        }

        if (e.target.classList.contains('dlt-btn')) {
            const rateId = e.target.dataset.id;
            if (!confirm("Are you sure you want to delete this rate?")) return;

            const mutation = `
                mutation DeleteParkingRate($id: ID!) {
                    deleteParkingRate(id: $id) {
                        status
                        message
                    }
                }
            `;
            console.log(rateId)
            const variables = { id: rateId };

            try {
                const res = await fetch('/graphql', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                    },
                    body: JSON.stringify({ query: mutation, variables })
                });

                const data = await res.json();

                if (data.errors) {
                    alert(data.errors[0].message);
                } else {
                    alert(data.data.deleteParkingRate.message || "Deleted successfully!");
                    location.reload();
                }
            } catch (err) {
                console.error(err);
                alert("Something went wrong.");
            }
        }
    });

    document.getElementById('cancelEdit').addEventListener('click', () => {
        document.getElementById('editModal').classList.add('hidden');
    });

    // TODO:edit
    // document.getElementById('editParkingRateForm').addEventListener('submit', async function (e) {
    //     e.preventDefault();

    //     const id = document.getElementById('edit-id').value;
    //     const title = document.getElementById('edit-title').value;
    //     const hours = document.getElementById('edit-hours').value;
    //     const fees = document.getElementById('edit-fees').value;
    //     const description = document.getElementById('edit-description').value;
    //     const remark = document.getElementById('edit-remark').value;

    //     const mutation = `
    //         mutation UpdateParkingRate($id: ID!, $title: String!, $hours: String!, $fees: String!, $description: String!, $remark: String) {
    //             updateParkingRate(id: $id, title: $title, hours: $hours, fees: $fees, description: $description, remark: $remark) {
    //                 parkingrate {
    //                     id
    //                     title
    //                 }
    //             }
    //         }
    //     `;

    //     const variables = { id, title, hours, fees, description, remark };

    //     try {
    //         const res = await fetch('/graphql', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'Accept': 'application/json',
    //                 'Authorization': 'Bearer ' + localStorage.getItem('authToken')
    //             },
    //             body: JSON.stringify({ query: mutation, variables })
    //         });

    //         const data = await res.json();
    //         if (data.errors) {
    //             alert(data.errors[0].message);
    //         } else {
    //             alert("Updated successfully!");
    //             document.getElementById('editModal').classList.add('hidden');
    //             location.reload(); // or manually update the row
    //         }
    //     } catch (err) {
    //         console.error(err);
    //         alert("Something went wrong.");
    //     }
    // });

</script>
@endsection
