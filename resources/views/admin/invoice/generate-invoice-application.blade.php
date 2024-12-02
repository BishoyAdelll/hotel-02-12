<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice {{$record->id}}</title>

    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }
        h1,h2,h3,h4,h5,h6,p,span,label {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }
        table thead th {
            height: 28px;
            text-align: left;
            font-size: 16px;
            font-family: sans-serif;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }
        .imageHandle{
            width: 140px !important;
            height: 110px !important;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }
        .small-heading {
            font-size: 18px;
            font-family: sans-serif;
        }
        .total-heading {
            font-size: 18px;
            font-weight: 700;
            font-family: sans-serif;
        }
        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }
        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {
            text-align: left;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .company-data span {
            margin-bottom: 4px;
            display: inline-block;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: 400;
        }
        .no-border {
            border: 1px solid #fff !important;
        }
        .bg-blue {
            background-color: #229799;
            color: #fff !important;
        }
    </style>
</head>
<body>

    <table class="order-details">
        <thead>

            <tr>

                <th width="50%" colspan="2" >
                    <div>
                        <img src="https://i.imgur.com/dHAcQey.jpeg" alt="" class="imageHandle" >
                    </div>
                    {{--                    <h2 >welcome to  {{(__('filament.church'))}}</h2>--}}
                </th>

                <th width="50%" colspan="2" class="text-end company-data">
                    <span>Invoice Id: {{$record->id}}</span> <br>

                    <span>Date & Time: {{$record->created_at}}</span> <br>

                    <span>Zip code :  </span> <br>

                    {{-- <span>Room Number: {{$record->hall->number}} </span> <br> --}}


                    {{-- <a href="{{$record->hall->structure->code}}" type="button">Location </a><br> --}}

                </th>
            </tr>
            <tr class="bg-blue">
                <th width="50%" colspan="2">Customer Details</th>
                <th width="50%" colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Order Id:</td>
                <td>{{$record->number}}</td>

                <td>Full Name:</td>
                <td>{{$record->customer->full_name}}</td>
            </tr>
            <tr>

                <td>Church Name</td>
                <td>
                    {{$record->customer->church_name}}
                </td>
                <td>Whatsapp:</td>
                <td>{{$record->customer->whatsapp}}</td>
            </tr>
            <tr>
                <td>Ordered Date:</td>
                <td>{{$record->created_at}}</td>

                <td>Phone:</td>
                <td>{{$record->customer->phone}}</td>
            </tr>
            <tr>
                <td>Order Status:</td>
                <td>{{$record->status}}</td>
                <td>Payment :</td>
                <td>{{$record->payment}}</td>
            </tr>
            <tr>
                <td>Responsible name:</td>
                <td>{{$record->customer->responsible_name}}</td>


            </tr>

            <tr class="bg-blue">
                <th  class="total-heading" style="text-align:center" colspan="4"> reservation date </th>
            </tr>
            <tr >
                <td style="text-align:center" colspan="4">{{$record->start_date}}</td>
            </tr>


        </tbody>
    </table>
    @if ($record->attachments)
        <table style="margin-top: 8px">
            <thead>
            <tr class="bg-blue" >
                <th>name</th>
                <th>check In</th>
                <th>check out</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($record->attachments as $item)
                <tr>
                    {{-- @dd($item) --}}
                    <td>{{$item->attachment->name}}</td>
                    <td>{{\Illuminate\Support\Carbon::parse($item->start_time)->format('d-m-Y h:i A')}}</td>
                    <td>{{\Illuminate\Support\Carbon::parse($item->end_time)->format('d-m-Y h:i A')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <table>
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="5">
                    Order Items
                </th>
            </tr>

        </thead>

        <tbody>

        <tr class="bg-blue">
            <th colspan="1" class="total-heading" style="text-align:center">Add on Name </th>
            <th colspan="2" >Addition Description</th>
            <th colspan="1" >Addition quantity</th>
            <th colspan="1" >price </th>
            <th colspan="1" >total Price</th>
        </tr>
{{--         @dd($tests)--}}
       @if($tests)
           @foreach ($tests as $product )
               <tr>

                   <td colspan="1" >{{$product->addition->name}}</td>
                   <td colspan="2" style="text-align: center"> <span class="font2">{{$product->addition->description}}</span> </td>

                   <td colspan="1" style="text-align: center">({{$product->quantity}}) </td>
                   <td colspan="1" >{{$product->price}} EG</td>
                   <td colspan="1" >{{$product->total}} EG</td>
               </tr>
           @endforeach
       @endif

{{--        @dd($meals);--}}
        @if($meals)
            @foreach ($meals as $product )
                <tr>
                    <td colspan="1" >{{$product->type}}</td>
                    <td colspan="2" style="text-align: center"> <span class="font2">{{$product->component->title}}</span> </td>
                    <td colspan="1" style="text-align: center">({{$product->quantity}})</td>
                    <td colspan="1" >{{$product->price}} EG</td>
                    <td colspan="1" >{{$product->price *$product->quantity}} EG</td>
                </tr>
            @endforeach
        @endif


            <tr>
                <td colspan="5" class="total-heading">deposit</td>

                <td colspan="3"  >{{$record->deposit}} EG</td>
            </tr>
            <tr class="bg-blue">
                <th colspan="3" class="total-heading" style="text-align:center">Total invoice </th>
                <th colspan="1" >tax</th>
                <th colspan="1" >discount </th>
                <th colspan="1" >Total invoice </th>
            </tr>
            <tr>
                <td colspan="3" class="total-heading">Total Amount - <small>Inc. all vat/tax</small> :</td>
                <td colspan="1" >{{$record->tax}} <span style="float: right">TAX</span></td>
                <td colspan="1" >{{$record->discount}} <span style="float: right">%</span></td>
                <td colspan="1" >{{$record->grand_total}} <span style="float: right">EG</span></td>
            </tr>

        </tbody>
    </table>

    <br>
    <p class="text-center">
        Thank your for booking on Welcome to the hotel
    </p>
{{--    <button onclick="window.print()">print This  page </button>--}}
</body>
</html>
