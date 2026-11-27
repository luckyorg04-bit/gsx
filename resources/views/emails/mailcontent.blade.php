@component('mail::message')

<div style="padding: 0 0 18px; border-bottom: 2px solid #0f172a; margin-bottom: 24px;">
    <span style="display: inline-block; background: #0f172a; color: #ffffff; font-weight: 700; letter-spacing: 2px; padding: 10px 14px; border-radius: 8px;">GSX</span>
    <span style="display: inline-block; margin-left: 10px; color: #0f172a; font-weight: 700;">School Management Platform</span>
</div>

{!! html_entity_decode($content) !!}

<hr style="border: 0; border-top: 1px solid #e5e7eb; margin-top: 28px;">
<p style="color: #64748b; font-size: 12px;">This message was sent by GSX. Please contact your school administrator if you need assistance.</p>

@endcomponent
