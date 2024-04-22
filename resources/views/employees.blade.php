<table>
    <thead>
        <tr>
            <th>Employee</th>
            @foreach ($dates as $date)
                <th>{{ $date->format('d.m.Y') }}</th>
            @endforeach
            <th>Workdays Last Month</th>
            <th>Workhours</th>
            <th>Norm</th>
            <th>Workhours - Norm</th>
            <th>Company BIN</th>
            <th>Company Title</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                @foreach ($dates as $date)
                    @php
                        $workday = $employee->workdays->firstWhere('date', $date->format('Y-m-d'));
                        $isHoliday = Holiday::where('start_date', '<=', $date)
                            ->where('end_date', '>=', $date)
                            ->exists();
                    @endphp
                    <td>
                    @if ($isHoliday && $employee->getShift()->work_days >= 5 && $workday->workhours == 0)
                        П
                    @else
                        {{ $workday ? $workday->workhours : '-' }}
                    @endif
                    </td>
                @endforeach
                <td>{{ $employee->workdays_last_month($year_month) ?? "0" }}</td>
                <td>{{ $employee->workhours($year_month) ?? "0" }}</td>
                <td>{{ $employee->norm($year_month) ?? "0" }}</td>
                <td>{{ strval(($employee->workhours($year_month) ?? 0) - ($employee->norm($year_month) ?? 0)) }}</td>
                <td>{{ " ".strval($employee->company->bin) }}</td>
                <td>{{ $employee->company->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>