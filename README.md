# M7 PCIS Payroll System

## Overview
The **M7 PCIS Payroll System** is a specialized payroll management solution developed for **M7 Philippine Cambridge School**. This application automates the calculation of employee salaries, manages earnings and deductions, and generates official, system-branded PDF payslips.

## Key Features

- **Employee Management**:
  - Tracks employee details including Name, Position, and Employee ID.
- **Payroll Processing**:
  - **Earnings**: Calculates Basic Pay and Allowances.
  - **Deductions**: Manages and subtracts deductions from the gross salary.
  - **Net Salary**: Automatically computes the final take-home pay.
- **Payslip Generation**:
  - Generates official PDF payslips.
  - Includes company branding (Logo, Address, Contact Info).
  - Displays detailed breakdown of earnings and deductions.
- **Period Management**:
  - Supports specific pay periods (e.g., "End-Month") with conditional calculation logic.

## Technology Stack

- **Framework**: Laravel (PHP)
- **Frontend**: Blade Templates, HTML, CSS
- **PDF Engine**: DOMPDF (or compatible Laravel PDF wrapper)
- **Database**: MySQL

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository_url>
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database credentials in the `.env` file.*

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

## Screenshots

*The screenshots for the system are located locally at: `screenshots`*

## 📸 System Screenshots

### Landing Page
![Landing Page](screenshots/landing.png)

### Login Page
![Login Page](screenshots/login.png)

### Dashboard
![Dashboard](screenshots/dashboard.png)

### Leave Management
![Leave Management](screenshots/leaves.png)

### Employee Management
![Employee Management](screenshots/employees.png)

### Student Management
![Student Management](screenshots/student.png)

### Approvals
![Approvals Management](screenshots/approvals.png)

### Attendance Management
![Attendance Management](screenshots/attendance.png)

### Payroll Processing
![Payroll Processing](screenshots/payroll.png)

### Profile Management
![Profile Management](screenshots/profile.png)

### Payslip Generation
![Payslip Generation](screenshots/payslip.png)



