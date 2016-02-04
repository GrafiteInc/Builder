<div class="container">

    {{ $team->name }}

    <h2>Members</h2>
    <table>
        <thead>
            <th>Name</th>
        </thead>
        <tbody>
            @foreach($team->members as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
