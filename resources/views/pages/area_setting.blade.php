@extends('layout.app')

@section('content')
<div class="w-full space-y-8">

    {{-- Add new parking rate --}}
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
        <h1 class="text-xl font-semibold mb-4">Fill in a new area data below</h1>

        <form id="createArea" class="space-y-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-1">
                    * Area
                </label>
                <input id="title" name="title" type="text" placeholder="Area"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-2">
                    * Normal Parking Space amount
                </label>
                <input id="normal_parking_space_amount" name="normal_parking_space_amount" type="text" placeholder="Normal Parking Space amount"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-3">
                    * OKU Parking Space amount
                </label>
                <input id="oku_parking_space_amount" name="oku_parking_space_amount" type="text" placeholder="OKU Parking Space amount"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="parking-rate">
                    * Parking rate
                </label>
                <select id="parking-rate" name="parking-rate"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Please create at least 1 parking rate first.</option>
                </select>
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
        <h1 class="text-xl font-semibold mb-4">Current area data</h1>

        <table class="table-fixed w-full bg-white shadow rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="w-1/2 px-4 py-2 text-left">Area</th>
                    <th class="w-1/4 px-4 py-2 text-left">Available Parking Space (normal)</th>
                    <th class="w-1/4 px-4 py-2 text-left">User(s)</th>
                    <th class="w-1/4 px-4 py-2 text-left">Available Parking Space (OKU)</th>
                    <th class="w-1/4 px-4 py-2 text-left">User(s)</th>
                    <th class="w-1/4 px-4 py-2 text-left"></th>
                    <th class="w-1/4 px-4 py-2 text-left"></th>
                </tr>

            <tbody id="list-of-area">
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
        <form id="editAreaFormn">
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
            window.location.href = '/';
            return;
        }

        const parkingRateDropdown = document.getElementById('parking-rate');
        const parkingRateQuery = `
            query {
                parkingrates {
                    id
                    title
                }
            }
        `;

        try {
            const res = await fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify({ query: parkingRateQuery })
            });

            const data = await res.json();
            const rates = data.data.parkingrates || [];

            // Clear existing options
            parkingRateDropdown.innerHTML = '';

            if (rates.length === 0) {
                parkingRateDropdown.disabled = true;
                const option = document.createElement('option');
                option.text = 'No parking rates available';
                option.disabled = true;
                option.selected = true;
                parkingRateDropdown.appendChild(option);
                return;
            }

            // Add default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Select a parking rate';
            defaultOption.disabled = true;
            defaultOption.selected = true;
            parkingRateDropdown.appendChild(defaultOption);

            // Populate dropdown
            rates.forEach(rate => {
                const option = document.createElement('option');
                option.value = rate.id;
                option.text = rate.title;
                parkingRateDropdown.appendChild(option);
            });

        } catch (error) {
            console.error('Failed to load parking rates:', error);
        }

        // list of area
        const tbody = document.getElementById('list-of-area');
        const areaQuery = `
            query {
                areas {
                    id
                    title
                    parking_space_normal
                    parking_space_oku
                    ParkingRate {
                        title
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
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                body: JSON.stringify({ query: areaQuery })
            });

            const result = await res.json();
            const areas = result.data?.areas || [];
            console.log(areas)
            if (areas.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center px-4 py-2 text-gray-500">No area data available.</td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';

            areas.forEach((area, index) => {
                const rowClass = index % 2 === 1 ? 'bg-gray-50' : '';
                const normalUsers = Array.isArray(area.parking_space_normal_user) ? area.parking_space_normal_user.length : 0;
                const okuUsers = Array.isArray(area.parking_space_oku_user) ? area.parking_space_oku_user.length : 0;

                const row = `
                    <tr class="${rowClass}">
                        <td class="border px-4 py-2">${area.title || '-'}</td>
                        <td class="border px-4 py-2">${area.parking_space_normal || '-'}</td>
                        <td class="border px-4 py-2">${normalUsers}</td>
                        <td class="border px-4 py-2">${area.parking_space_oku || '-'}</td>
                        <td class="border px-4 py-2">${okuUsers}</td>
                        <td class="border px-4 py-2">${area.ParkingRate?.title || '-'}</td>
                        <td class="border px-4 py-2">
                            <button 
                                class="delete-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none dlt-btn focus:shadow-outline"
                                data-id="${area.id}"
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
            console.error('Error loading areas:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center px-4 py-2 text-red-500">Failed to load areas.</td>
                </tr>
            `;
        }
        // list of area

    });

    // create area
    document.getElementById('createArea').addEventListener('submit', async function (e) {
        e.preventDefault();

        const title = document.getElementById('title').value;
        const parking_space_normal = document.getElementById('normal_parking_space_amount').value;
        const parking_space_oku = document.getElementById('oku_parking_space_amount').value;
        const parking_rate_id = parseInt(document.getElementById('parking-rate').value);
        const responseElement = document.getElementById('responseMessage');

        const query = `
            mutation createArea($title: String!, $parking_space_normal: String!, $parking_space_oku: String!, $parking_rate_id: Int!) {
                createArea(title: $title, parking_space_normal: $parking_space_normal, parking_space_oku: $parking_space_oku, parking_rate_id: $parking_rate_id) {
                    area {
                        id
                        title
                        parking_space_normal
                        parking_space_oku
                        ParkingRate {
                            id
                            title
                        }
                    }
                }
            }
        `;

        const variables = {
            title,
            parking_space_normal,
            parking_space_oku,
            parking_rate_id,
        };

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
            console.log(data)
            if (data.errors) {
                responseElement.textContent = data.errors[0].message;
                responseElement.className = 'text-red-600';
            } else {
                responseElement.textContent = 'Area created successfully';
                responseElement.className = 'text-green-600';
                document.getElementById('createArea').reset();
                location.reload();

            }
        } catch (error) {
            console.error(error);
            responseElement.textContent = "Something went wrong.";
            responseElement.className = 'text-red-600';
        }
    });
    // create area

    document.getElementById('list-of-area').addEventListener('click', async function (e) {
        if (e.target.classList.contains('dlt-btn')) {
            const rateId = e.target.dataset.id;
            if (!confirm("Are you sure you want to delete this rate?")) return;

            const mutation = `
                mutation DeleteArea($id: ID!) {
                    deleteArea(id: $id) {
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
                    alert(data.data.deleteArea.message || "Deleted successfully!");
                    location.reload();
                }
            } catch (err) {
                console.error(err);
                alert("Something went wrong.");
            }
        }

    });
</script>
@endsection
