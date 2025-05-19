@extends('layout.app')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Mark your parking below</h1>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">

        <form id="createParkingRate" class="space-y-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-1">
                    * Area
                </label>
                <input id="area" name="area" type="text" placeholder="Area"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            {{-- availbility here --}}

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-2">
                    * IN Time
                </label>
                <input id="in-time" name="in-time" type="text" placeholder="IN Time"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            {{-- <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full-name-3">
                    OUT Time
                </label>
                <input id="fees" name="fees" type="text" placeholder="OUT Time"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div> --}}


            <div class="flex items-center">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create
                </button>
            </div>
        </form>
    </div>
    <p id="responseMessage" class="text-green-600 font-semibold mt-4"></p>

    {{-- parked detail here --}}
@endsection