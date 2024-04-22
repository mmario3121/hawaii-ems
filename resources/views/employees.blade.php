<table>
    <thead>
        <tr>
            <th>Employee</th>
            @foreach ($dates as $date)
                <th>{{ $date->format('d.m.Y') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                @foreach ($dates as $date)
                    @php
                        $workday = $employee->workdays->firstWhere('date', $date->format('Y-m-d'));
                    @endphp
                    <td>{{ $workday ? $workday->workhours : '-' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>