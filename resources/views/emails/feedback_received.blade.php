@component('mail::message')
# Phản hồi mới từ website

- **Tên:** {{ $feedback->name }}
- **Email:** {{ $feedback->email }}

**Nội dung:**

{{ $feedback->message }}

---

ID phản hồi: {{ $feedback->id }}

@endcomponent
