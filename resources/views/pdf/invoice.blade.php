<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice for {{ $student->full_name }}</title>
    <link rel="stylesheet" href="{{ asset('/uikit-3.1.7/css/uikit.min.css') }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: rgba(0, 0, 0, .7);
            font-size: 0.8em;
        }

        .wrapper {
            margin: 0 auto;
            display: block;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="uk-table uk-table-small">
            <thead>
                <tr>
                    <th class="uk-width-auto">&nbsp;</th>
                    <th class="uk-width-auto">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <h1 class="uk-heading-medium">ELOSpeak</h1>
                        <ul class="uk-list">
                            <li>Bldg. 32, Gangnam Street</li>
                            <li>Seoul, South Korea</li>
                            <li>051-212-3456</li>
                        </ul>
                        <hr>
                        <span class="uk-text-lead">Bill To:</span>
                        <ul class="uk-list">
                            <li>{{ $student->full_name }}</li>
                            <li>Email: {{ $student->email }}</li>
                            <li>Skype: {{ $student->skype }}</li>
                            <li>{{ $student->personal_contact_number }}</li>
                        </ul>
                    </td>

                    <td>
                        <div class="uk-text-right">
                            <h3 class="uk-heading-small">Invoice</h3>
                            <dl class="uk-description-list">
                                <dt><strong>Date Issued</strong></dt>
                                <dd>{{ date('F j Y', strtotime($invoice->created_at)) }}</dd>
                            </dl>

                            <dl class="uk-description-list">
                                <dt><strong>Invoice ID</strong></dt>
                                <dd>{{ $invoice->id }}</dd>
                            </dl>

                            <dl class="uk-description-list">
                                <dt><strong>Transaction ID</strong></dt>
                                <dd>{{ $transaction_id }}</dd>
                            </dl>

                            <dl class="uk-description-list">
                                <dt><strong>Student ID</strong></dt>
                                <dd>{{ $student->id }}</dd>
                            </dl>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table class="uk-table uk-table-small uk-table-divider">
            <thead>
                <tr>
                    <th class="uk-table-expand">Description</th>
                    <th class="uk-table-shrink">Qty</th>
                    <th class="uk-width-auto">Unit Price</th>
                    <th class="uk-width-small">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classes as $day => $timeSlots)
                    <tr>
                        <td colspan="3">{{ ucfirst($day) }} class</td>
                        <td>&nbsp;</td>
                    </tr>

                    @foreach ($timeSlots as $timeSlot)
                        <tr>
                            <td>
                                <div class="uk-margin-medium-left">{{ $timeSlot[0]}} &#8212; {{ $timeSlot[1] }}</div>
                            </td>
                            <td>1</td>
                            <td>
                                @php
                                    $price = 0;
                                    if ($day == 'saturday' || $day == 'sunday'):
                                        $price = $pricePerClassWeekend;
                                    else:
                                        $price = $pricePerClassWeekday;
                                    endif;
                                @endphp

                                {{ $price }} KRW
                            </td>
                            <td>{{ $price }} KRW</td>
                        </tr>
                    @endforeach
                @endforeach
                
                <tr>
                    <td class="uk-text-right" colspan="3">
                        TOTAL WEEKLY
                    </td>
                    <td>
                        <strong>{{ $totalAmount }}</strong>
                    </td>
                </tr>
                <tr>
                    <td class="uk-text-right" colspan="3">
                        TOTAL NUMBER OF WEEKS
                    </td>
                    <td>
                        <strong>4</strong>
                    </td>
                </tr>
                <tr>
                    <td class="uk-text-right" colspan="3">
                        TOTAL AMOUNT
                    </td>
                    <td>
                        <strong>{{ $totalAmount * 4 }} KRW</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>