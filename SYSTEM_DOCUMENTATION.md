# SYSTEM DOCUMENTATION - SIPENDA (Sistem Informasi PPDB)

## 1. System Overview
SIPENDA (Sistem Informasi PPDB) is a web-based application designed to manage the New Student Admission (PPDB/Penerimaan Peserta Didik Baru) process. It facilitates the registration of students across various levels (SD, SMP, SMA) through different paths (Zonasi, Prestasi, Afirmasi, etc.) and provides dashboards for students, school operators, and administrators to manage the selection process.

## 2. Architecture & Design Pattern (MVC)
The application is built using the **Laravel Framework (v12.0)**, enabling a robust Model-View-Controller (MVC) architecture.

### A. Models (Data Layer)
Located in `app/Models/`. These classes represent the database structure and relationships.
*   **Student**: Represents the student/applicant data (biodata, scores, documents).
*   **School**: Represents the school data (name, quota, location).
*   **Operator**: Represents the school operators who manage admissions for a specific school.
*   **User**: Base user model (potentially for super admins).

### B. Views (Presentation Layer)
Located in `resources/views/`. These are Blade templates that render the user interface.
*   **Public Pages**:
    *   `index.blade.php`: Landing page.
    *   `daftar-sekolah.blade.php`: Search and filter schools (Zonasi, logic implemented here).
*   **Dashboards**:
    *   `operator_dashboard.blade.php`: Interface for school operators to verify and manage applicants.
    *   `student/dashboard.blade.php` (Assumed): Student's personal dashboard.
*   **Authentication**:
    *   `auth/register-operator.blade.php`: Registration for operators.
    *   `layouts/*`: Master layouts for consistent styling.

### C. Controllers (Logic Layer)
Located in `app/Http/Controllers/`. Checkpoint for handling user requests and business logic.
*   **PPDBController**: The core controller handling the main PPDB logic (school search, registration processing, API endpoints).
*   **StudentAuthController**: Manages student authentication (login/register).
*   **OperatorAuthController**: Manages operator authentication.
*   **AdminAuthController**: Manages administrator authentication.

## 3. Technology Stack

### Backend
*   **Framework**: Laravel 12.0
*   **Language**: PHP 8.2+
*   **Database**: MySQL / SQLite (configurable)

### Frontend
*   **Templating Engine**: Blade
*   **Styling**: Tailwind CSS (Utility-first CSS framework)
*   **Scripting**: Vanilla JavaScript + Axios (for AJAX/API requests)
*   **Assets**: Vite (Build tool)

## 4. Recent Logic & Feature Updates

### A. Dynamic Dropdown Logic (Daftar Sekolah)
*   **Feature**: Conditional visibility of the "Kecamatan" filter.
*   **Logic**: The Kecamatan dropdown is hidden by default. It is Programmatically revealed via JavaScript only when the user selects the **"Zonasi"** registration path. This ensures a cleaner UI for non-zoning paths where district specificity is less critical/irrelevant.
*   **Implementation**: `resources/views/daftar-sekolah.blade.php` (JS Event Listeners).

### B. Operator Dashboard Enhancements
*   **Feature**: Enhanced Document Verification UI.
*   **Styling**: Document links (KK, Akta, Ijazah) are now styled as **"Pill Buttons"** with icons and clear text labels for better accessibility and clickability.
*   **Logic**:
    *   **Standard Documents**: Styled with a **Blue** theme `[📄 Document Name]`.
    *   **Prestasi Documents**: Styled with a **Purple** theme `[🏆 Bukti Prestasi]` to distinctively highlight achievement proofs for the "Prestasi" path.
*   **Implementation**: `resources/js/components/operator.js` (Dynamic HTML generation).

## 5. Directory Structure Summary
```
/app
  /Http/Controllers  # Request Logic
  /Models            # Database Models
/resources
  /views             # HTML/Blade Files
  /js                # JavaScript Logic (e.g., operator.js)
  /css               # Application Styles
/routes
  web.php            # Web Routes
  api.php            # API Routes
```
