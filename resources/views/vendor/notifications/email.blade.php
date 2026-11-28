@component('mail::message')
<div style="padding: 0 0 18px; border-bottom: 2px solid #0f172a; margin-bottom: 24px;">
    <span style="display: inline-block; background: #0f172a; color: #ffffff; font-weight: 700; letter-spacing: 2px; padding: 10px 14px; border-radius: 8px;">GSX</span>
    <span style="display: inline-block; margin-left: 10px; color: #0f172a; font-weight: 700;">School Management Platform</span>
</div>

@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

@foreach ($introLines as $line)
{{ $line }}

@endforeach

@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

@foreach ($outroLines as $line)
{{ $line }}

@endforeach

@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>{{ config('app.name', 'GSX') }}
@endif

<div style="border-top: 1px solid #e5e7eb; margin-top: 24px; padding-top: 12px; color: #64748b; font-size: 12px;">
    This message was sent by GSX. Please contact your school administrator if you need assistance.
</div>

@isset($actionText)
@component('mail::subcopy')
@lang(
    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endcomponent
@endisset
@endcomponent
