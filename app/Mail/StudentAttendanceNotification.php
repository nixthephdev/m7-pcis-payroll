<?php

namespace App\Mail;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentAttendanceNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Student $student,
        public Attendance $attendance,
    ) {}

    public function envelope(): Envelope
    {
        $status = $this->attendance->status;
        $name   = $this->student->full_name;

        return new Envelope(
            subject: "Attendance Alert: {$name} — {$status}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.student_attendance');
    }
}
