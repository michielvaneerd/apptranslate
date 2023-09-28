<?php extract($table); ?>
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
                {{ $item->$column }}
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>