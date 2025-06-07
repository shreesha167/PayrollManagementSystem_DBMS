# Payroll Management System (PMS)

A web-based Payroll Management System (PMS) designed to streamline employee management, attendance tracking, and salary processing. This system uses **PHP** and **MySQL** to manage employee records, departments, attendance, and payroll via a user-friendly web interface.

## 📌 Features

- Employee registration and management
- Department and project tracking
- Attendance and salary record handling
- Stored procedures for salary calculation based on attendance
- Admin dashboard for CRUD operations
- Responsive frontend with functional admin panel

## 🧰 Technologies Used

- PHP (Server-side logic)
- MySQL (Database)
- HTML/CSS/JS (Frontend)
- XAMPP/WAMP for local server environment

## ⚙️ Database Schema

Tables:
- `register`
- `employee`
- `salary`
- `department`
- `attendance`
- `project`

Stored Procedure:
```sql
CREATE PROCEDURE UpdateSalary(IN salary INT)
BEGIN
    UPDATE employee AS e
    JOIN attendance AS a ON e.emp_id = a.emp_id
    SET e.salary = 
        CASE 
            WHEN a.days_worked BETWEEN 10 AND 12 THEN e.salary / 3.1 
            WHEN a.days_worked BETWEEN 13 AND 15 THEN e.salary / 2.1 
            WHEN a.days_worked BETWEEN 16 AND 20 THEN e.salary / 1.1 
            ELSE e.salary
        END;
END
