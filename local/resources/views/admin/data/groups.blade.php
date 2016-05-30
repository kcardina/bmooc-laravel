@extends('admin.data.master')

@section('content')
<div class="row">
    <div class="columns">
        <table>
            <thead>
                <tr>
                    <td>GROUP</td>
                    <td>USERS</td>
                    <td>active</td>
                    <td>passive</td>
                    <td>TOPICS</td>
                    <td>POSTS</td>
                    <td>group</td>
                    <td>other</td>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr>
                    <td>
                        {{ $group->name }}
                    </td>
                    <td>
                        {{ sizeof($group->users) }}
                    </td>
                    <?php
                        $active = 0;
                        $passive = 0;
                        $posts = 0;
                        $fromgroup = 0;
                        $fromother = 0;
                        $topics = [];
                        foreach($group->users as $user){
                            if(sizeof($user->artefacts) > 0) $active++;
                            else $passive++;

                            $posts += sizeof($user->artefacts);

                            foreach($user->artefacts as $artefact){
                                if(!in_array($artefact->thread, $topics)) array_push($topics, $artefact->thread);

                                $in = false;
                                foreach($group->topics as $topic){
                                    if($artefact->thread == $topic->thread) $in = true;
                                }
                                if($in) $fromgroup++;
                                else $fromother++;
                            }
                        }
                    ?>
                    <td>
                        {{$active}}
                        @if(sizeof($group->users) > 0)
                        <small>({{ round($active / sizeof($group->users) * 100) }}%)</small>
                        @endif
                    </td>
                    <td>
                        {{$passive}}
                        @if(sizeof($group->users) > 0)
                        <small>({{ round($passive / sizeof($group->users) * 100) }}%)</small>
                        @endif
                    </td>
                    <td>
                        {{sizeof($topics)}}
                    </td>
                    <td>
                        {{$posts}}
                    </td>
                    <td>
                        {{$fromgroup}}
                    </td>
                    <td>
                        {{$fromother}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection


@section('scripts')
    @parent

@endsection
