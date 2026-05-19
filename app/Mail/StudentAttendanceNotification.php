<?php

namespace App\Mail;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentAttendanceNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Student $student,
        public Attendance $attendance,
        public string $clockType = 'clock_in',
    ) {}

    public function envelope(): Envelope
    {
        $name  = $this->student->full_name;
        $label = $this->clockType === 'clock_out' ? 'Clock Out' : $this->attendance->status;

        return new Envelope(
            subject: "Attendance Alert: {$name} — {$label}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.student_attendance');
    }
}
