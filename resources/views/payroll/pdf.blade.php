<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payslip</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 12px; margin: 0; padding: 0; }
        
        /* SIDEBAR */
        .sidebar { position: absolute; top: 0; left: 0; bottom: 0; width: 25px; background-color: #312e81; }
        
        .container { padding: 30px 40px 30px 60px; }

        /* HEADER */
        .header-table { width: 100%; margin-bottom: 25px; border-bottom: 2px solid #312e81; padding-bottom: 15px; }
        .company-name { font-size: 20px; font-weight: bold; color: #312e81; margin: 0; }
        .company-info { font-size: 10px; color: #555; line-height: 1.4; margin-top: 5px; }
        .doc-label { font-size: 14px; font-weight: bold; color: #888; text-transform: uppercase; letter-spacing: 2px; text-align: right; margin-top: 10px;}

        /* EMPLOYEE INFO */
        .info-box { width: 100%; margin-bottom: 20px; background-color: #f9fafb; padding: 15px; border-radius: 5px; }
        .info-table { width: 100%; }
        .label { font-weight: bold; color: #666; font-size: 10px; text-transform: uppercase; }
        .value { font-size: 12px; font-weight: bold; color: #111; padding-bottom: 8px; }

        /* DETAILS TABLE */
        .details-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .details-table th { text-align: left; color: #312e81; font-size: 10px; uppercase; border-bottom: 2px solid #312e81; padding: 8px 0; }
        .details-table td { padding: 6px 0; border-bottom: 1px solid #eee; font-size: 11px; }
        
        .col-left { width: 50%; padding-right: 15px; vertical-align: top; }
        .col-right { width: 50%; padding-left: 15px; vertical-align: top; border-left: 1px solid #eee; }

        .amount { text-align: right; font-weight: bold; }
        .text-red { color: #dc2626; }

        /* TOTALS */
        .total-box { margin-top: 15px; width: 100%; }
        .total-label { font-weight: bold; color: #555; font-size: 11px; }
        .total-amount { text-align: right; font-size: 12px; font-weight: bold; }

        /* NET PAY CARD */
        .net-pay-card { background-color: #312e81; color: white; padding: 15px; margin-top: 20px; border-radius: 5px; }
        .net-label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        .net-amount { font-size: 22px; font-weight: bold; text-align: right; margin: 0; }

        .footer { position: fixed; bottom: 30px; left: 60px; right: 40px; font-size: 9px; color: #aaa; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    
    <div class="sidebar"></div>

    <div class="container">
        
        <!-- HEADER WITH LOGO & INFO -->
        <table class="header-table">
            <tr>
                <!-- LOGO (Left) -->
                <td style="width: 25%; vertical-align: top;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" style="height: 90px; width: auto;">
                    @endif
                </td>
                
                <!-- COMPANY INFO (Center/Right) -->
                <td style="width: 75%; vertical-align: top; padding-left: 15px;">
                    <h1 class="company-name">M7 Philippine Cambridge International School</h1>
                    <div class="company-info">
                        Km. 25 Gen. Aguinaldo Highway, Anabu II-D, City of Imus, Cavite, Philippines<br>
                        Cavite: +6346 458 6588 &nbsp;|&nbsp; Mobile: +63 917 7217 800<br>
                        Website: www.pcis.edu.ph
                    </div>
                    <div class="doc-label">OFFICIAL PAYSLIP</div>
                </td>
            </tr>
        </table>

        <!-- EMPLOYEE INFO -->
        <div class="info-box">
            <table class="info-table">
                <tr>
                    <td width="35%">
                        <div class="label">Employee Name</div>
                        <div class="value">{{ optional($payroll->employee->user)->name }}</div>
                    </td>
                    <td width="35%">
                        <div class="label">Position</div>
                        <div class="value">{{ $payroll->employee->position }}</div>
                    </td>
                    <td width="30%">
                        <div class="label">Employee ID</div>
                        <div class="value">{{ str_pad($payroll->employee->employee_code, 4, '0', STR_PAD_LEFT) }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Pay Period</div>
                        <div class="value">{{ $payroll->period }}</div>
                    </td>
                    <td>
                        <div class="label">Date Generated</div>
                        <div class="value">{{ \Carbon\Carbon::now()->format('F d, Y') }}</div>
                    </td>
                    <td>
                        <div class="label">Payroll Ref</div>
                        <div class="value">#{{ str_pad($payroll->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- CALCULATIONS -->
        @php
            $allowances = $payroll->employee->salaryItems->where('type', 'earning');
            $totalAllowances = $allowances->sum('amount');
            $deductions = $payroll->employee->salaryItems->where('type', 'deduction');
            
            $displayBasic = $payroll->gross_salary;
            if($payroll->period == 'End-Month') {
                $displayBasic = $payroll->gross_salary - $totalAllowances;
            }
        @endphp

        <!-- DETAILS TABLE -->
        <table style="width: 100%;">
            <tr>
                <td class="col-left">
                    <table class="details-table">
                        <thead><tr><th>EARNINGS</th><th style="text-align:right;">AMOUNT</th></tr></thead>
                        <tbody>
                            <tr>
                                <td>Basic Pay</td>
                                <td class="amount">PHP {{ number_format($displayBasic, 2) }}</td>
                            </tr>
                            @if($payroll->period == 'End-Month')
                                @foreach($allowances as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td class="amount">PHP {{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    
                    <div class="total-box">
                        <table width="100%">
                            <tr>
                                <td class="total-label">TOTAL GROSS</td>
                                <td class="total-amount">PHP {{ number_format($payroll->gross_salary, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>

                <td class="col-right">
                    <table class="details-table">
                        <thead><tr><th>DEDUCTIONS</th><th style="text-align:right;">AMOUNT</th></tr></thead>
                        <tbody>
                            @if($payroll->period == 'End-Month')
                                @foreach($deductions as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td class="amount text-red">PHP {{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="2" style="color:#999; font-style:italic;">No deductions</td></tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="total-box">
                        <table width="100%">
                            <tr>
                                <td class="total-label">TOTAL DEDUCTIONS</td>
                                <td class="total-amount text-red">- PHP {{ number_format($payroll->deductions, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- NET PAY -->
        <table width="100%" class="net-pay-card">
            <tr>
                <td style="vertical-align: middle;">
                    <div class="net-label">NET SALARY (TAKE HOME PAY)</div>
                </td>
                <td style="vertical-align: middle;">
                    <div class="net-amount">PHP {{ number_format($payroll->net_salary, 2) }}</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <table width="100%">
                <tr>
                    <td width="50%">System Generated by M7 PCIS Payroll System</td>
                    <td width="50%" style="text-align: right;">Page 1 of 1</td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>