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
            <th width="30">Должность</th>
            <th>Смена</th>
            <th>Оклад</th>
            <th>Часы</th>
            <th>Норма</th>
            <th>Ставка тенге/час</th>
            <th>ПРЕМИЯ часы</th>
            <th>Премия к рассчету</th>
            <th class="wide">Компания</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td width="30">{{ $employee->name }}</td>
                <td width="5">{{ $employee->position->title }}</td>
                <td width="5">{{ $employee->getShiftName() }}</td>
                <td>{{ number_format($employee->salary_gross, 0, '', '') }}</td>
                <td>{{ $employee->workhours($year_month) ?? 0 }}</td>
                <td>{{ $employee->norm($year_month) ?? 0 }}</td>
                <td>{{ $employee->hourly_rate($year_month) }}</td>
                <td>{{ $employee->overtime($year_month) }}</td>
                <td>{{ $employee->overtime_salary($year_month) }}</td>
                <td width="20">{{ $employee->company->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>