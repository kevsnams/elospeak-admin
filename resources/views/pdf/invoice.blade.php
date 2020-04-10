<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice for {{ $student->full_name }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css') }}">
    <style>
        body {
            /*font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;*/
            color: #212529;
        }

        h1 {
            font-size: 2rem;
            font-weight: bolder;
        }

        .sub-h {
            font-size: 1rem;
            display: block;
        }

        .slash {
            color: #212529;
            font-weight: bold;
        }

        h1.invoice {
            font-size: 1.8rem;
            font-weight: bolder;
        }
    </style>
</head>
<body>
    <?php
        $contacts = array_filter([
            env('COMPANY_WEBSITE'),
            env('COMPANY_TELEPHONE'),
            env('COMPANY_EMAIL')
        ], function ($env) {
            return filled($env);
        });

        $studentContacts = array_filter([
            $student->full_name,
            $student->email,
            $student->personal_contact_number
        ], function ($info) {
            return filled($info);
        });

        $units = $items->groupBy('type')->sortBy('start');
    ?>

    <table class="table table-borderless">
        <tbody>
            <tr>
                <td>
                    <h1>{{ env('COMPANY_NAME', 'ELOSpeak Pty') }}</h1>

                    @if (filled(env('COMPANY_ADDRESS_1')))
                        <span class="sub-h text-secondary">{{ env('COMPANY_ADDRESS_1') }}</span>
                    @endif

                    @if (filled(env('COMPANY_ADDRESS_2')))
                        <span class="sub-h text-secondary">{{ env('COMPANY_ADDRESS_2') }}</span>
                    @endif

                    @if (!empty($contacts))
                        <span class="sub-h text-secondary">
                            {!! implode(' <span class="slash">/</span> ', $contacts) !!}
                        </span>
                    @endif
                </td>
                <td>
                    <h1 class="invoice text-muted text-right">INVOICE</h1>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-right"><strong>Date:</strong></td>
                                <td>{{ $enrollment->created_at->format('F j, Y')}}</td>
                            </tr>

                            <tr>
                                <td class="text-right"><strong>Invoice #:</strong></td>
                                <td>INV{{ $enrollment->id }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td class="text-right">
                    <strong>Bill To:</strong>
                </td>
                <td>
                    @if (count($studentContacts))
                        @foreach ($studentContacts as $contact)
                            <span class="d-block">{{ $contact }}</span>
                        @endforeach
                    @endif
                </td>
                <td>
                    <strong class="d-block" style="font-size: 1.5rem;">
                        Total Invoice
                    </strong>
                    <strong class="d-block" style="font-size: 2rem;">
                        {{ $country->currency_code }} 
                        {{
                            number_format($units->sum(function ($unit) {
                                return $unit->sum('price');
                            }), 2)
                        }}
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <table class="mt-4 table table-sm">
        <thead class="thead-light">
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($units as $label => $group)

                <tr>
                    <td>{{ $label }}</td>
                    <td colspan="3">{{ $group->count() }}</td>
                </tr>
                
                @foreach ($group as $unit)
                    <tr>
                        <td>
                            <span class="ml-5">{{ $unit['start']->format('F d, Y h:i A') }} &#8212; {{ $unit['end']->format('h:i A') }}</span>
                        </td>
                        <td>&nbsp;</td>
                        <td>{{ $country->currency_code }} {{ number_format($unit['is_weekend'] ? $country->price_weekend : $country->price, 2) }}</td>
                        <td>&nbsp;</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td>
                        <strong>{{ $country->currency_code }} {{ number_format($group->sum('price'), 2) }}</strong>
                    </td>
                </tr>

            @endforeach
            <tr>
                <td colspan="2">&nbsp;</td>
                <td>
                    <strong style="font-size: 1.5rem;">Total</strong>
                </td>
                <td>
                    <strong style="font-size: 1.5rem;">
                        {{ $country->currency_code }} 
                        {{
                            number_format($units->sum(function ($unit) {
                                return $unit->sum('price');
                            }), 2)
                        }}
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>