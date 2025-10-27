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
    <td>{{ $item->product->code }}</td>
    <td>{{ $item->product->name }}</td>
    <td>{{ $item->product->bike }}</td>
    <td>{{ $item->product->model }}</td>
    <td><input type="number" value="{{ $item->qty }}" id="qty{{ $item->id }}" onfocusout="qty({{ $item->id }})"></td>
    <td><input type="number" value="{{ $item->rate }}" id="rate{{ $item->id }}" onfocusout="rate({{ $item->id }})"></td>
    <td>{{ $item->retail }}</td>
    <td>{{ $item->wholesale }}</td>
    <td>{{ $amount }}</td>
    <td><button class="btn btn-danger" onclick="deleteDraft({{ $item->id }})">Delete</button></td>
</tr>
@endforeach
<tr>
    <td colspan="9" style="text-align: right;"><strong>{{ __('lang.Total') }}</strong></td>
    <td style="text-align: center;"><strong>{{ $total }}</strong></td>
    <td></td>
</tr>
