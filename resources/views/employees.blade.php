<?php
use App\Models\Holiday;
?>
<style>
    th, td {
        width: 50px !important;
        height: 50px !important;
        text-align: center;
    }
    .wide {
        width: 200px !important;
    }
</style>
<table>
    <thead>
        <tr>
            <th class="wide">ФИО</th>
            @foreach ($dates as $date)
                <th>{{ $date->format('d') }}</th>
            @endforeach
            <th>Дни</th>
            <th>Часы</th>
            <th>Норма</th>
            <th>+/-</th>
            <th class="wide">БИН</th>
            <th class="wide">Компания</th>
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
                        $isWorkday = $workday && $workday->isWorkday;

                    @endphp
                    <td style="
                        {{ $workday && $workday->absence ? 'background-color: ' . $workday->absence->color . ';' : '' }}
                        {{ $isHoliday ? 'background-color: red;' : '' }}
                        {{ $isWorkday ? 'background-color: green;' : '' }}
                    ">
                        @if ($workday && $workday->absence)
                            {{ $workday->absence->type }}
                        @elseif ($isHoliday && $employee->getShift()->work_days >= 5 && $workday->workhours == 0)
                            П
                        @else
                            {{ $workday ? $workday->workhours : '-' }}
                        @endif
                    </td>
                @endforeach
                <td>{{ $employee->workdays_last_month($year_month) ?? "0" }}</td>
                <td>{{ $employee->workhours($year_month) ?? "0" }}</td>
                <td>{{ $employee->norm($year_month) ?? "0" }}</td>
                @php
                    $workhoursNormDiff = ($employee->workhours($year_month) ?? 0) - ($employee->norm($year_month) ?? 0);
                @endphp
                <td style="color: {{ $workhoursNormDiff >= 0 ? 'green' : 'red' }}">
                    {{ strval($workhoursNormDiff) }}
                </td>
                <td>{{ " ".strval($employee->company->bin) }}</td>
                <td>{{ $employee->company->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>