@extends('layouts.app')

@section('content')
    <h1>Student Bookings</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Slot 1</th>
                <th>Slot 2</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($slots as $date => $slotData)
                <tr>
                    <td>{{ $date }}</td>
                    @for ($i = 1; $i <= 2; $i++)
                        <td>
                            @if (isset($slotData[$i]))
                                {{ $slotData[$i]['status'] }}<br>
                                Student: {{ $slotData[$i]['student'] ?? 'N/A' }}<br>
                                @if ($slotData[$i]['meeting_link'])
                                    <a href="{{ $slotData[$i]['meeting_link'] }}">Join</a>
                                @else
                                    <form method="POST" action="{{ route('teacher.bookings.upload-link', $slotData[$i]['booking'] ? $slotData[$i]['booking']->id : '') }}">
                                        @csrf
                                        <input type="url" name="meeting_link" placeholder="Meeting Link">
                                        <button type="submit">Upload</button>
                                    </form>
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                    @endfor
                    <td>
                        @foreach($slotData as $slotNum => $data)
                            <form method="POST" action="{{ route('teacher.update-slot-status', $slotData[$slotNum]['booking'] ? $slotData[$slotNum]['booking']->slot_id : '') }}">
                                @csrf
                                <select name="status">
                                    <option value="pending" {{ $data['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $data['status'] == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rescheduled" {{ $data['status'] == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection