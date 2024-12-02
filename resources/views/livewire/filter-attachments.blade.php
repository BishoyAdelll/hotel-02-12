<div>

    <div style="display: flex; justify-content: space-between ;align-items: center" >
        <div>
            <input type="datetime-local" wire:model="startDate" >
            <input type="datetime-local" wire:model="endDate"  >

        </div>

        <button wire:click="save()" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                <span class="fi-btn-label">
                filter
                </span>
        </button>

    </div>
    <div>
        <h2 class="text-2xl my-4"> Reserved Rooms </h2>
    </div>
    <div class="container mx-auto" style="margin-top: 20px">
        <div class="shadow-lg  overflow-hidden mx-4 md:mx-10">
            <table class="w-full table-fixed">
                <thead>
                <tr style="background-color: #ee9797">
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">name</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">description</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">CheckIn</th>
                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">CheckOut</th>
{{--                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">OrderNumber</th>--}}
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">

                @forelse($reservedAttachments as $attachment)
                    <tr>
{{--                        @dd($attachment)--}}
                        <td class="py-4 px-6 border-b border-gray-200">{{$attachment->attachment->name}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{$attachment->attachment->name}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{\Illuminate\Support\Carbon::parse($attachment->start_time)->format('d-m-Y h:i A')}}</td>
                        <td class="py-4 px-6 border-b border-gray-200">{{\Illuminate\Support\Carbon::parse($attachment->end_time)->format('d-m-Y h:i A')}}</td>


                @empty
                    <tr>
                        <td class="py-4 px-6 border-b border-gray-200 text-center" colspan="8"><h2>Now Data Found </h2></td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
