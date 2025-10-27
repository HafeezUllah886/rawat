@php
    $ser = 0;
    $amount = 0;
    $total = 0;
@endphp
@foreach ($items as $item)
@php
    $ser += 1;
    $price = $item->price - $item->discount;
    $amount = $item->qty * $price;
    $total += $amount;
@endphp
<tr>
    <td>{{ $ser }}</td>
    <td>{{ $item->product1->name }}</td>
    <td>{{ $item->product1->bike }}</td>
    <td>{{ $item->product1->model }}</td>
    <td>{{ $item->product1->brand }}</td>
    <td><input type="number" class="text-center" value="{{ $item->qty }}" id="qty{{ $item->id }}" onfocusout="qty({{ $item->id }})"></td>
    <td><input type="number" class="text-center" value="{{ $item->price }}" id="rate{{ $item->id }}" onfocusout="rate({{ $item->id }})"></td>
    <td><input type="number" class="text-center" value="{{ $item->discount }}" id="discount{{ $item->id }}" onfocusout="rate({{ $item->id }})"></td>
    <td>{{ $amount }}</td>
    <td><a class="btn btn-danger" href="{{ url('/quotation/details/delete/') }}/{{ $item->id }}" >Delete</a></td>
</tr>
@endforeach
<tr>
    <td colspan="8" style="text-align: right;"><strong>Total</strong></td>
    <td style="text-align: center;"><strong>{{ $total }}</strong></td>
    <td></td>
</tr>
