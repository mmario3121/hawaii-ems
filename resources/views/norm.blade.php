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
            <th width="20">Должность</th>
            <th width="10">Номер</th>
            <th width="10">Смена</th>
            @foreach ($dates as $date)
                <th>{{ $date->format('d') }}</th>
            @endforeach
            <th>Дни</th>
            <th>Часы</th>
            <th>Норма</th>
            <th class="wide">БИН</th>
            <th class="wide">Компания</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td width="30">{{ $employee->name }}</td>
                <td width="20">{{ $employee->position->title }}</td>
                <td width="10">{{ $employee->number }}</td>
                <td width="10">{{ $employee->getShiftName() }}</td>
                @foreach ($dates as $date)
                    @php
                        $workday = $employee->workdays->firstWhere('date', $date->format('Y-m-d'));
                    @endphp
                    <td style="
                        {{ $workday && $workday->isWorkday == 1 ? 'background-color: green;' : '' }}
                        {{ $workday && !$workday->isWorkday  ? 'background-color: #7a7a7a;' : '' }}
                    " width="3">
                            {{ $workday && $workday->isWorkday == 1 ? 8 : 'В' }}
                    </td>
                @endforeach
                <td width="5">{{ $employee->workdays_last_month($year_month) ?? "0" }}</td>
                <td width="5">{{ $employee->workhours($year_month) ?? "0" }}</td>
                <td width="6">{{ $employee->norm($year_month) ?? "0" }}</td>
                <td width="20">{{ number_format($employee->company->bin, 0, '', '') }}</td>
                <td width="20">{{ $employee->company->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>