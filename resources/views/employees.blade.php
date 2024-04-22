<table>
    <thead>
        <tr>
            <th>Employee</th>
            <th>Workdays Last Month</th>
            <th>Workhours</th>
            <th>Norm</th>
            <th>Workhours - Norm</th>
            <th>Company BIN</th>
            <th>Company Title</th>
            @foreach ($dates as $date)
                <th>{{ $date->format('d.m.Y') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->workdays_last_month($year_month) ?? "0" }}</td>
                <td>{{ $employee->workhours($year_month) ?? "0" }}</td>
                <td>{{ $employee->norm($year_month) ?? "0" }}</td>
                <td>{{ strval(($employee->workhours($year_month) ?? 0) - ($employee->norm($year_month) ?? 0)) }}</td>
                <td>{{ " ".strval($employee->company->bin) }}</td>
                <td>{{ $employee->company->title }}</td>
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