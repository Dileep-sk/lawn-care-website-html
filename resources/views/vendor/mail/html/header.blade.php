@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img src="{{ url('icon/icon.png') }}" alt="Company Logo" height="50">
            @else
                {!! $slot !!}
            @endif
        </a>
    </td>
</tr>
