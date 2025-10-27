@php
    $ser = 0;
    $amount = 0;
    $total = 0;
@endphp
@foreach ($items as $item)
@php
    $ser += 1;
    $amount = $item->qty * $item->rate;
    $total += $amount;
@endphp
<tr>
    <td>{{ $ser }}</td>
    <td>{{ $item->product->name }}</td>
    <td>{{ $item->product->size }}</td>
    <td><input type="number" value="{{ $item->qty }}" id="qty{{ $item->id }}" onfocusout="qty({{ $item->id }})"></td>
    <td><input type="number" value="{{ $item->rate }}" id="rate{{ $item->id }}" onfocusout="rate({{ $item->id }})"></td>
    <td>{{ $amount }}</td>
    <td><button class="btn btn-danger" onclick="deleteEdit({{ $item->id }})">Delete</button></td>
</tr>
@endforeach
<tr>
    <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
    <td style="text-align: center;"><strong>{{ $total }}</strong></td>
    <td></td>
</tr>
