@php
    $ser = 0;
    $amount = 0;
    $total = 0;
@endphp
@foreach ($items as $item)
@php
    $ser += 1;
    $amount = $item->qty * ($item->price - $item->discount);
    $total += $amount;
@endphp
<tr>
    <td>{{ $ser }}</td>
    <td>{{ $item->product->code }}</td>
    <td>{{ $item->product->name }}</td>
    <td>{{ $item->product->bike }}</td>
    <td>{{ $item->product->model }}</td>
    <td>{{ $item->product->brand }}</td>
    <td><input type="number" value="{{ $item->qty }}" id="qty{{ $item->id }}" onfocusout="qty({{ $item->id }})"></td>
    <td><input type="number" value="{{ round($item->price,0) }}" id="rate{{ $item->id }}" onfocusout="rate({{ $item->id }})"></td>
    <td><input type="number" value="{{ round($item->discount,0) }}" id="discount{{ $item->id }}" onfocusout="discount({{ $item->id }})"></td>
    <td>{{ $amount }}</td>
    <td><button class="btn btn-danger" onclick="deleteDraft({{ $item->id }})">Delete</button></td>
</tr>
@endforeach
<tr>
    <td colspan="9" style="text-align: right;"><strong>Total</strong></td>
    <td style="text-align: center;"><strong>{{ $total }}</strong></td>
    <td></td>
</tr>
