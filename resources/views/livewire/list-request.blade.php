<div>
    <div class="shadow-lg rounded-lg overflow-hidden mx-4 md:mx-10">
        <table class="w-full table-fixed">
            <thead>
            <tr class="bg-gray-50">
                <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Church</th>
                <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Email</th>
                <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Phone</th>
                <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Status</th>
            </tr>
            </thead>
            <tbody class="bg-white">
            @foreach($Requests as $request)
                <tr>
                    <td class="py-4 px-6 border-b border-gray-200">{{$request->church}}</td>
                    <td class="py-4 px-6 border-b border-gray-200 truncate">{{$request->email}}</td>
                    <td class="py-4 px-6 border-b border-gray-200">{{$request->phone}}</td>
                    @if($request->status === 1)
                        <td  class="py-4 px-6 border-b border-gray-200">
                            <span class="bg-green-500 text-white py-1 px-2 rounded-full text-xs">Accepted</span>
                        </td>
                    @else
                        <td  class="py-4 px-6 border-b border-gray-200">
                            <span class="bg-red-500 text-white py-1 px-2 rounded-full text-xs">Binding</span>
                        </td>
                    @endif
                </tr>
            @endforeach

            <!-- Add more rows here -->
            </tbody>
        </table>
    </div>
</div>
