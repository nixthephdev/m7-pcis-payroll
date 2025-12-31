<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payslip</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 12px; }
        .container { width: 100%; padding: 20px; }
        
        .header { text-align: center; border-bottom: 2px solid #312e81; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #312e81; text-transform: uppercase; }
        .header p { margin: 5px 0; color: #666; font-size: 10px; }

        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .label { font-weight: bold; color: #555; }

        .details-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .details-table th { background-color: #e5e7eb; padding: 8px; text-align: left; border: 1px solid #ccc; font-size: 10px; uppercase; }
        .details-table td { padding: 8px; border: 1px solid #ccc; vertical-align: top; }
        
        .text-right { text-align: right; }
        .text-green { color: #166534; }
        .text-red { color: #dc2626; }
        .font-bold { font-weight: bold; }
        
        .total-row { background-color: #312e81; color: white; font-weight: bold; }
        .subtotal-row { background-color: #f3f4f6; font-weight: bold; }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #aaa; }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- HEADER -->
        <div class="header">
            <!-- LOGO -->
            <img src="{{ public_path('images/logo.png') }}" style="height: 60px; width: auto; margin-bottom: 5px;">
            <h1>M7 PCIS</h1>
            <p>OFFICIAL PAYSLIP DOCUMENT</p>
        </div>

        <!-- EMPLOYEE INFO -->
        <table class="info-table">
            <tr>
                <td class="label">Employee Name:</td>
                <td>{{ optional($payroll->employee->user)->name }}</td>
                <td class="label">Date Generated:</td>
                <td>{{ \Carbon\Carbon::parse($payroll->pay_date)->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Position:</td>
                <td>{{ $payroll->employee->position }}</td>
                <td class="label">Payroll ID:</td>
                <td>#{{ str_pad($payroll->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
        </table>

        <!-- CALCULATIONS -->
        @php
            // Calculate Total Allowances to separate them from Basic Pay
            $allowances = $payroll->employee->salaryItems->where('type', 'earning');
            $totalAllowances = $allowances->sum('amount');
            
            // Basic Pay = Gross - Allowances
            $basicPay = $payroll->gross_salary - $totalAllowances;

            // Deductions
            $deductions = $payroll->employee->salaryItems->where('type', 'deduction');
        @endphp

        <!-- DETAILS TABLE -->
        <table class="details-table">
            <thead>
                <tr>
                    <th width="50%">EARNINGS</th>
                    <th width="50%">DEDUCTIONS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <!-- EARNINGS COLUMN -->
                    <td>
                        <table width="100%">
                            <tr>
                                <td>Basic Pay (Attendance Based)</td>
                                <td class="text-right">PHP {{ number_format($basicPay, 2) }}</td>
                            </tr>
                            
                            <!-- Loop through Allowances -->
                            @foreach($allowances as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td class="text-right">PHP {{ number_format($item->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>

                    <!-- DEDUCTIONS COLUMN -->
                    <td>
                        <table width="100%">
                            <!-- Loop through Deductions -->
                            @foreach($deductions as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td class="text-right text-red">PHP {{ number_format($item->amount, 2) }}</td>
                            </tr>
                            @endforeach
                            
                            @if($deductions->isEmpty())
                                <tr><td colspan="2" style="text-align:center; color:#999;">No Deductions</td></tr>
                            @endif
                        </table>
                    </td>
                </tr>

                <!-- SUBTOTALS -->
                <tr class="subtotal-row">
                    <td>
                        <table width="100%">
                            <tr>
                                <td>TOTAL GROSS</td>
                                <td class="text-right">PHP {{ number_format($payroll->gross_salary, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td>TOTAL DEDUCTIONS</td>
                                <td class="text-right text-red">- PHP {{ number_format($payroll->deductions, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- NET PAY -->
                <tr class="total-row">
                    <td colspan="2" style="padding: 15px;">
                        <table width="100%">
                            <tr>
                                <td style="color:white; font-size: 14px;">NET SALARY (TAKE HOME PAY)</td>
                                <td class="text-right" style="color:white; font-size: 16px;">PHP {{ number_format($payroll->net_salary, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>
        <p><strong>Status:</strong> <span class="text-green font-bold">PAID</span></p>

        <div class="footer">
            System Generated by M7 PCIS Payroll System. Valid without signature.
        </div>

    </div>
</body>
</html>