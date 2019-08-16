<div id="sched-error" class="uk-margin-small-top"></div>

@foreach ($days as $day)
    <tr>
        <td>
            <input type="checkbox" class="uk-checkbox sched-day-checkbox" data-bouncer-target="#sched-error" data-check-once="sched-day" onclick="toggleSched(event, this)" name="classroom_schedule_preference[{{ strtolower($day['name']) }}][checked]">
        </td>
        <td>
            {{ $day['name'] }}
        </td>
        <td>
            <select class="uk-select sched-slots" required data-skip-disable data-bouncer-target="#error-slot-{{ $day['name'] }}" name="classroom_schedule_preference[{{ strtolower($day['name']) }}][slots][]" disabled>
                <option value="">Select time slot</option>
                @foreach ($timeSlots as $timeSlot)
                    <option value="{{ implode('|', $timeSlot) }}">
                        {!! implode(' &#8212; ', $timeSlot); !!}
                    </option>
                @endforeach
            </select>
            <small id="error-slot-{{ $day['name'] }}" class="error-float-away"></small>
            <div id="add-sched-{{ $day['name'] }}"></div>
        </td>
        <td>
            <button class="uk-button uk-button-default uk-button-small" onclick="addSched(event, '{{ $day['name'] }}')" disabled uk-tooltip="title: Add More">
                <span uk-icon="icon: plus"></span>
            </button>
        </td>
    </tr>
@endforeach