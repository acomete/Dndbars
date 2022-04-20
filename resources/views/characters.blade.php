<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>D&D</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito';
        }
    </style>
</head>
<body>
<div class="container">
    <div class="character-blocks">
        @foreach($characters as $character)
            @if ($character && isset($character->name))
                <div class="character-block">
                    <div class="avatar">
                        <img src="{{$character->decorations['avatarUrl']}}" alt="{{$character->name}}"
                        />
                        @if ($character->decorations['frameAvatarUrl'])
                            <img src="{{$character->decorations['frameAvatarUrl']}}" alt="{{$character->name}}"
                                 style="width: 150px; height: 150px;"
                            />
                        @endif
                    </div>
                    <div class="stats">
                        <div class="name">{{$character->name}}</div>
                        <div class="bar">
                            <span style="width: {{$character->hps->current*100/$character->hps->max}}%">
                            </span>
                        </div>
                        @if($character->mana->max > 0)
                            <div class="bar mana">
                            <span style="width: {{$character->mana->current*100/$character->mana->max}}%">
                            </span>
                            </div>
                        @endif
                        @if($character->ki->max > 0)
                            <div class="bar ki">
                            <span style="width: {{$character->ki->current*100/$character->ki->max}}%">
                            </span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="ml-12">
                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                        Le personnage est innacessible.
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
</body>
</html>
