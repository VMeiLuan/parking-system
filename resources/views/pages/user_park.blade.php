@extends('layout.app')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Mark your parking below</h1>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">

        <form id="createParkingRate" class="space-y-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="area">
                    * Area
                </label>
                <select id="area" name="area"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Please choose 1 area.</option>
                </select>

                <div id="availability" class="text-sm text-gray-700 mt-2"></div>

            </div>

            <div class="flex gap-4 mt-4">
                <button id="inNormalBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    IN for Normal Space
                </button>
                <button id="inOkuBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    IN for OKU Space
                </button>
            </div>        
        </form>
    </div>
    <p id="responseMessage" class="text-green-600 font-semibold mt-4"></p>

    {{-- parked detail here --}}
    <div>
        <h1 class="text-xl font-semibold mb-4">My Parking History</h1>

        <table class="table-fixed w-full bg-white shadow rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="w-1/6 px-4 py-2 text-left">Date In</th>
                    <th class="w-1/6 px-4 py-2 text-left">Area</th>
                    <th class="w-1/6 px-4 py-2 text-left">Fees</th>
                    {{-- <th class="w-1/6 px-4 py-2 text-left">Total Payment</th> --}}
                    <th class="w-1/6 px-4 py-2 text-left">Payment Status</th>
                    <th class="w-1/6 px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody id="user-parking-list">
                <tr>
                    <td colspan="6" class="text-center px-4 py-2 text-gray-500">Loading user parking records...</td>
                </tr>
            </tbody>
        </table>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        const token = localStorage.getItem('authToken');
        const userData = localStorage.getItem('authUser');
        const now = new Date();

        if (!token) {
            window.location.href = '/';
            return;
        }

        const parkingRateDropdown = document.getElementById('area');
        const availabilityDiv = document.getElementById('availability'); 
        const inNormalBtn = document.getElementById('inNormalBtn');
        const inOkuBtn = document.getElementById('inOkuBtn');

        const parkingRateQuery = `
            query {
                areas {
                    id
                    title
                    parking_space_normal
                    parking_space_oku
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
            const rates = data.data.areas || [];

            parkingRateDropdown.innerHTML = '';

            if (rates.length === 0) {
                parkingRateDropdown.disabled = true;
                const option = document.createElement('option');
                option.text = 'No area available';
                option.disabled = true;
                option.selected = true;
                parkingRateDropdown.appendChild(option);
                return;
            }

            // default
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Select an area';
            defaultOption.disabled = true;
            defaultOption.selected = true;
            parkingRateDropdown.appendChild(defaultOption);

            // Populate dropdown with data-* attributes
            rates.forEach(rate => {
                const option = document.createElement('option');
                option.value = rate.id;
                option.text = rate.title;
                option.dataset.normal = rate.parking_space_normal;
                option.dataset.oku = rate.parking_space_oku;
                parkingRateDropdown.appendChild(option);
            });

            // Handle dropdown change
            parkingRateDropdown.addEventListener('change', () => {
                const selected = parkingRateDropdown.options[parkingRateDropdown.selectedIndex];
                const normal = selected.dataset.normal || '-';
                const oku = selected.dataset.oku || '-';

                if (selected.value) {   
                    availabilityDiv.innerHTML = `
                        <p><strong>Available Normal Space:</strong> ${normal}</p>
                        <p><strong>Available OKU Space:</strong> ${oku}</p>
                    `;

                    inNormalBtn.textContent = `IN Normal @ ${now.toLocaleString()}`;
                    inOkuBtn.textContent = `IN OKU @ ${now.toLocaleString()}`;
                } else {
                    availabilityDiv.innerHTML = '';
                    inNormalBtn.textContent = 'IN for Normal Space';
                    inOkuBtn.textContent = 'IN for OKU Space';
                }
            });

            async function sendInMutation(areaId, userId, btnType, token, inDateTime) {
                const user = JSON.parse(userId);
                const mutation = `
                    mutation {
                        createNewParkedRecord(
                            area_id: "${areaId}",
                            custom_user_id: "${user.id}",
                            btn_type: "${btnType}",
                            in: "${inDateTime}"
                        ) {
                            status
                            message
                        }
                    }
                `;
                try {
                    const response = await fetch('/graphql', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify({ query: mutation })
                    });
                    
                    const result = await response.json();
                    console.log(result)
                    console.log('Mutation response:', result.data.createNewParkedRecord);
                    alert(result.data.createNewParkedRecord.message || 'Success');
                } catch (error) {
                    console.error('Mutation failed:', error);
                    alert('Failed to record IN time.');
                }
            }

            // Handle IN Normal button click
            inNormalBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const areaId = parkingRateDropdown.value;
                if (!areaId) {
                    alert('Please select an area first.');
                    return;
                }
                // console.log('IN Normal clicked for area ID:', areaId);
                // console.log(areaId, userData.id, "btn_normal", token)
                sendInMutation(areaId, userData, "btn_normal", token, now.toISOString().slice(0, 19).replace('T', ' '));
            });

            // Handle IN OKU button click
            inOkuBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const areaId = parkingRateDropdown.value;
                if (!areaId) {
                    alert('Please select an area first.');
                    return;
                }
                console.log('IN OKU clicked for area ID:', areaId);
                sendInMutation(areaId, userData, "btn_oku", token, now.toISOString().slice(0, 19).replace('T', ' '));
            });

        } catch (error) {
            console.error('Failed to load parking rates:', error);
        }

        // user parking history
        const tbody = document.getElementById('user-parking-list');
        const userParkingQuery = `
            query {
                parkeds {
                    id
                    in
                    payment_status
                    Area {
                        title
                        ParkingRate {
                            fees
                        }
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
                body: JSON.stringify({ query: userParkingQuery })
            });

            const result = await res.json();
            const parkeds = result.data?.parkeds || [];
            console.log(result)
            if (parkeds.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center px-4 py-2 text-gray-500">No user parking data available.</td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';

            parkeds.forEach((parked, index) => {
                const rowClass = index % 2 === 1 ? 'bg-gray-50' : '';

                const row = `
                    <tr class="${rowClass}">
                        <td class="border px-4 py-2">${parked.in || '-'}</td>
                        <td class="border px-4 py-2">${parked.Area?.title || '-'}</td>
                        <td class="border px-4 py-2">RM ${parked.Area?.ParkingRate?.fees || '-'}</td>
                        <td class="border px-4 py-2">
                        <span class="${parked.payment_status == 0 ? 'text-red-500 font-semibold' : 'text-green-600 font-semibold'}">
                            ${parked.payment_status == 0 ? 'Pending' : 'Paid'}
                        </span>
                        </td>
                        <td class="border px-4 py-2">
                            <button 
                                class="delete-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none dlt-btn focus:shadow-outline"
                                data-id="${area.id}"
                                style="cursor:pointer"
                            >
                                Payment
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });

            const form = document.getElementById('createParkingRate');
            if (parkeds.length > 0) {
                Array.from(form.elements).forEach(el => el.disabled = true);

                const message = document.createElement('div');
                message.className = 'text-red-500 mt-4 font-semibold';
                message.innerText = 'Please proceed with pending payment to do a new record.';
                form.appendChild(message);
            }
        } catch (error) {
            console.error('Error loading user parking data:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center px-4 py-2 text-red-500">Failed to load parking data.</td>
                </tr>
            `;
        }
        // user parking history
    });
</script>
@endsection