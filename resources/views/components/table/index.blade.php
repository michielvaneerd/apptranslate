<?php
extract($table);
$links = $links ?? [];
?>
<table class="table">
    <thead>
        <tr>
            @foreach($columns as $column)
            <th>
                {{ $column }}
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            @foreach($columns as $column)
            <td>
                @if(!empty($links[$column]))
                <a href="{{ $links[$column]($item) }}">
                    @endif
                    {{ $item->$column }}
                    @if(!empty($links[$column]))
                </a>
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>