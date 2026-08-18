{{--
    Horizontal order-tracking progress line (parcel-tracker style).

    Usage:
    @include('partials.progress-tracker', [
        'steps' => [
            ['label' => 'Order Confirmed', 'icon' => 'fa-check-circle', 'state' => 'done', 'meta' => '12 Aug 2026'],
            ['label' => 'In Production',   'icon' => 'fa-cog',          'state' => 'active', 'meta' => null],
            ['label' => 'Loading',         'icon' => 'fa-ship',      'state' => 'upcoming', 'meta' => null],
        ],
        'shipMoving' => false,  // animated ship icon on the line after the active step
    ])

    states: done (green + check), active (orange + pulse), upcoming (grey)
--}}
@props(['steps' => [], 'shipMoving' => false])

<style>
    .tracker{display:flex;align-items:flex-start;padding:6px 4px 2px;}
    .tracker-step{display:flex;flex-direction:column;align-items:center;flex:0 0 auto;min-width:64px;max-width:130px;}
    .tracker-dot{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;position:relative;z-index:2;transition:all .25s ease;}
    .tracker-dot.done{background:#28a745;color:#fff;border:2px solid #28a745;}
    .tracker-dot.active{background:#fd7e14;color:#fff;border:2px solid #fd7e14;box-shadow:0 0 0 5px rgba(253,126,20,.18);animation:trackerPulse 1.5s ease-in-out infinite;}
    .tracker-dot.upcoming{background:#f1f3f5;color:#adb5bd;border:2px dashed #ced4da;}
    .tracker-label{margin-top:8px;font-size:11.5px;font-weight:600;text-align:center;line-height:1.25;color:#495057;text-transform:uppercase;letter-spacing:.3px;}
    .tracker-step:nth-child(odd) .tracker-label{display:inline-block;}
    .tracker-meta{margin-top:2px;font-size:10.5px;color:#868e96;text-align:center;white-space:nowrap;}
    .tracker-segment{flex:1 1 auto;min-width:26px;height:3px;background:#e9ecef;margin-top:17px;position:relative;}
    .tracker-segment.done{background:#28a745;}
    .tracker-ship{position:absolute;top:-10px;left:50%;color:#fd7e14;font-size:16px;animation:trackerSail 2.4s ease-in-out infinite;filter:drop-shadow(0 1px 2px rgba(0,0,0,.25));}
    @keyframes trackerPulse{
        0%,100%{box-shadow:0 0 0 5px rgba(253,126,20,.18);transform:scale(1);}
        50%{box-shadow:0 0 0 10px rgba(253,126,20,.06);transform:scale(1.08);}
    }
    @keyframes trackerSail{
        0%,100%{transform:translateX(-5px) rotate(-6deg);}
        50%{transform:translateX(6px) rotate(6deg);}
    }
</style>

<div class="tracker">
    @foreach($steps as $i => $step)
        <div class="tracker-step">
            <div class="tracker-dot {{ $step['state'] ?? 'upcoming' }}">
                @if(($step['state'] ?? '') === 'done')
                    <i class="fas fa-check"></i>
                @else
                    <i class="fas {{ $step['icon'] ?? 'fa-circle' }}"></i>
                @endif
            </div>
            <div class="tracker-label">{{ $step['label'] }}</div>
            @if(!empty($step['meta']))
                <div class="tracker-meta">{{ $step['meta'] }}</div>
            @endif
        </div>
        @unless($loop->last)
            <div class="tracker-segment {{ (($steps[$i + 1]['state'] ?? '') === 'done' || ($steps[$i + 1]['state'] ?? '') === 'active') ? 'done' : '' }}">
                @if($shipMoving && (($steps[$i]['state'] ?? '') === 'active'))
                    <i class="fas fa-ship tracker-ship"></i>
                @endif
            </div>
        @endunless
    @endforeach
</div>
