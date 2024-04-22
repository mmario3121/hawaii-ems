<table>
    @foreach ($employees as $employee)
        <tr>
            <td style="background-color: #f00;">{{ $employee->id }}</td>
            <td style="background-color: #0f0;">{{ $employee->name }}</td>
        </tr>
    @endforeach
</table>