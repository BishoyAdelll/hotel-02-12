<div>

    <div style="display: flex; justify-content: space-between ;align-items: center" >
    <div>
        <input type="date" wire:model="startDate" >
        <input type="date" wire:model="endDate"  >
        {{--        <input type="date" wire:model="endDate" wire:change="save()" >--}}
        <select name="capacity" wire:model="capacity" id="">
            <option value="">chose Capacity</option>
            @foreach($capacities as $capacity)
                <option value="{{$capacity->id}}">{{$capacity->capacity}}</option>
            @endforeach
        </select>
    </div>

{{--        <button wire:click="save()">click To Filter </button>--}}
        <button wire:click="save()" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                <span class="fi-btn-label">
                filter
                </span>
        </button>

    </div>
    <div>
        <h2 class="text-2xl my-4"> Available Rooms </h2>
    </div>
    <div class="container mx-auto" style="margin-top: 20px">
        <div class="shadow-lg  overflow-hidden mx-4 md:mx-10">
            <table class="w-full table-fixed">
                <thead>
                <tr style="background-color: #96ea96">
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Number</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Structure</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Capacity</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Floor</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">RoomPrice</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                @forelse($availableHalls as $hall)
                    <tr>
                        <td class="py-4 px-6 border-b border-gray-200 truncate">{{$hall->number}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{$hall->structure->details}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{$hall->capacity->capacity}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{$hall->floor}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{$hall->room_price}}</td>

                    </tr>

                @empty
                        <tr>
                            <td class="py-4 px-6 border-b border-gray-200 text-center" colspan="6"><h2>Now Data Found </h2></td>
                        </tr>
                @endforelse
                @if($availableHalls)
                    <tr>
                        <td class="py-4 px-6 border-b border-gray-200 text-left "  > total</td>
                        <td class="py-4 px-6 border-b border-gray-200 text-center" > {{$availableHalls->count()}}</td>
                    </tr>
                @endif

                </tbody>
            </table>
        </div>
    </div>
    <div>
        <h2 class="text-2xl my-4"> Reserved Rooms </h2>
    </div>
    <div class="container mx-auto" style="margin-top: 20px">
        <div class="shadow-lg  overflow-hidden mx-4 md:mx-10">
            <table class="w-full table-fixed">
                <thead>
                <tr style="background-color: #ee9797">

                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Number</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Structure</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Capacity</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Floor</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">RoomPrice</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">CheckIn</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">CheckOut</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">OrderNumber</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">

                @forelse($reservedRooms as $room)
                    <tr>
                        <td class="py-4 px-6 border-b border-gray-200 truncate">{{$room->number}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{$room->structure->details}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{$room->capacity->capacity}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{$room->floor}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{$room->room_price}}</td>
                        @if($room->reservations->count() > 0)
                            <td class="py-4 px-6 border-b border-gray-300" colspan="6">
                                <table class="w-full table-auto">
                                    <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-xs text-gray-500">Check In</th>
                                        <th class="px-4 py-2 text-xs text-gray-500">Check Out</th>
                                        <th class="px-4 py-2 text-xs text-gray-500">Appointment Number</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($room->reservations->where('check_in', '<=', $endDate)
                                            ->where('check_out', '>', $startDate) as $reservation)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $reservation->check_in }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $reservation->check_out }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $reservation->appointment->number }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b border-gray-300" colspan="6">No reservations found for {{ $specificDate }}</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td class="py-4 px-6 border-b border-gray-200 text-center" colspan="8"><h2>Now Data Found </h2></td>
                    </tr>
                @endforelse
                @if($reservedRooms)
                    <tr>
                        <td class="py-4 px-6 border-b border-gray-200 text-left "  > total</td>
                        <td class="py-4 px-6 border-b border-gray-200 text-center" > {{$reservedRooms->count()}}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
