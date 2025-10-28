<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title> Sale Details </title>
    <link rel="stylesheet" href="{{ asset('css/view.css') }}">
</head>

<div class="w-full sale-details-container m-2" style="padding: 20px;">

<table class="w-full order-details border-block divide-y divide-gray-400">
    <thead>
        <tr>
            <th width="50%" colspan="2" class="text-center text-2xl heading">
                 Sale Receipt
            </th>
            <th width="50%" colspan="2" class="font-semibold text-end">
                <span> Sale Id: -{{ $sale->id }}- </span> <br>
                <span> Sale Date: {{ $sale->sale_date }} </span> <br>
                <span> Zip code : 560077</span> <br>
                <span> Address: Busega, Kampala (Uganda) </span> <br>
            </th>

        </tr>
        <tr class="bg-cyan-500">
            <th width="50%" colspan="2"> Order Details</th>
            <th width="50%" colspan="2"> Customer Details</th>
        </tr>
    </thead>

        <tbody class="font-semibold">
            <tr>
                <td>Order Id:</td>
                <td> -{{ $sale->id }}- </td>

                <td> Customer:</td>
                <td> {{ $sale->customer->name ?? 'N/A' }} </td>
            </tr>
            <tr>
                <td>Tracking Id :</td>
                <td> Julius -{{ $sale->id }}- </td>

                <td>Customer's Email:</td>
                <td> {{ $sale->customer->email ?? 'N/A' }} </td>
            </tr>
            <tr>
                <td>Ordered Date:</td>
                <td> {{ $sale->sale_date }} </td>

                <td>Phone:</td>
                <td>  {{ $sale->customer->phone ?? 'N/A' }} </td>
            </tr>
            <tr>
                <td>Payment Mode:</td>
                <td> {{ $sale->paymentMethod->name ?? 'N/A' }} </td>

                <td>Address:</td>
                <td> {{ $sale->customer->address ?? 'N/A' }} </td>
            </tr>
            <tr>
                <td>Order Status:</td>
                <td> {{ ucfirst($sale->status) }} </td>

                <td>Pin code:</td>
                <td> # </td>
            </tr>
        </tbody>
    </table>

        <p class="font-bold text-start heading text-black ml-15">
            Financial Summary
        </p>

     <table>
        <thead>
            <tr class="font-semibold bg-cyan-500 text-black">
                <th> Sale ID</th>
                <th> Product</th>
                <th> Quantity</th>
                <th> Price </th>
                <th> Subtotal</th>
                <th> Discount</th>
                <th> Tax </th>
            </tr>
        </thead>

        <tbody class="font-semibold">
            @foreach ($sale->salesItems as $salesItem)
                <tr colspan="7">
                    <td> {{ -$sale->id }}-</td>
                    <td> {{ $salesItem->item->name ?? 'N/A' }} </td>
                    <td>  #{{ number_format($salesItem->quantity ?? 'N/A') }}KG </td>
                    <td> UGX{{ number_format($salesItem->price ?? 'N/A') }}/= </td>
                    <td> UGX{{ number_format($sale->subtotal ?? 'N/A') }}/= </td>
                    <td> UGX{{ number_format($sale->discount ?? 'N/A') }}/= </td>
                    <td> UGX{{ number_format($sale->tax ?? 'N/A') }}/= </td>
                </tr>
            @endforeach
        </tbody>
        </table>

        <p class="flex flex-col-3 justify-between pt-2">
            <strong colspan="7" class="total-heading">
                Total Amount: UGX{{ number_format($sale->tax ?? 'N/A') }}/=
            </strong>
            <span class="font-semibold">
                Paid Amount: UGX{{ number_format($sale->paid_amount ?? 'N/A') }}/=
            </span>
            <span class="font-semibold">
                Change: UGX{{ number_format($sale->change ?? 'N/A') }}/=
            </span>
        </p>  <br>

    <section>
        <hr class="pt-2">
            <span class="pt-2"> <strong>
                Additional notes:</strong> {{ $sale->paymentMethod->description }}
            </span>
    </section>

        <hr>

    <footer class="font-semibold flex flex-col-2  justify-center pt-4">
        <span> Thank your for shopping with  &copy; Kalonde Julius
            <em class="px-1 font-italic-bold">
                {{ $currentYear = now()->year }}
            </em>

            <button onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                </svg>
            </button>
        </span>

    </footer>

</div>
