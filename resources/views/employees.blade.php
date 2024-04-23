<?php
use App\Models\Holiday;
?>
<style>
    table {
            width: 100%;
            page-break-inside: avoid;
            min-height: 100vh;
        }

        table, th, td {
            border: 1px solid #000;
            border-collapse: collapse;

            border-bottom: 1px solid #fff;
        }

        thead tr th:nth-child(1) {
            width: 29%;
        }

        thead tr th:nth-child(2) {
            width: 24%;
        }

        thead tr th:nth-child(3) {
            width: 20%;
        }

        thead tr th:nth-child(4) {
            width: 10%;
        }

        thead tr th:nth-child(5) {
            width: 18%;
        }
</style>
<table>
    <thead>
        <tr>
            <th width="30">ФИО</th>
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
                <td width="30">{{ $employee->name }}</td>
                @foreach ($dates as $date)
                    @php
                        $workday = $employee->workdays->firstWhere('date', $date->format('Y-m-d'));
                        $isHoliday = Holiday::where('start_date', '<=', $date)
                                            ->where('end_date', '>=', $date)
                                            ->exists();
                    @endphp
                    <td style="
                        {{ $workday && $workday->absence ? 'background-color: ' . $workday->absence->color . ';' : '' }}
                        {{ $isHoliday ? 'background-color: red;' : '' }}
                        {{ $workday && $workday->isWorkday == 1 ? 'background-color: green;' : '' }}
                        {{ $workday && !$workday->isWorkday && !$isHoliday && !$workday->absence == 1 ? 'background-color: grey;' : '' }}
                    " width="3">
                        @if ($workday && $workday->absence)
                            {{ $workday->absence->type }}
                        @elseif ($isHoliday && $employee->getShift()->work_days >= 5 && $workday->workhours == 0)
                            П
                        @else
                            {{ $workday ? $workday->workhours : '-' }}
                        @endif
                    </td>
                @endforeach
                <td width="5">{{ $employee->workdays_last_month($year_month) ?? "0" }}</td>
                <td width="5">{{ $employee->workhours($year_month) ?? "0" }}</td>
                <td width="6">{{ $employee->norm($year_month) ?? "0" }}</td>
                @php
                    $workhoursNormDiff = ($employee->workhours($year_month) ?? 0) - ($employee->norm($year_month) ?? 0);
                @endphp
                <td style="color: {{ $workhoursNormDiff >= 0 ? 'green' : 'red' }}" width="5">
                    {{ strval($workhoursNormDiff) }}
                </td>
                <td width="15">{{ " ".strval($employee->company->bin) }}</td>
                <td width="20">{{ $employee->company->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>