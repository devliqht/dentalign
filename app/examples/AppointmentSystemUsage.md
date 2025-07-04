# Appointment System Usage Guide

This guide explains how to use the new PatientRecord and AppointmentReport models that have been implemented in the dental clinic management system.

## Overview

The system now automatically creates:
- A `PatientRecord` when a new patient is registered
- An `AppointmentReport` when a new appointment is created

## Models

### PatientRecord Model
Located: `app/models/PatientRecord.php`

**Properties:**
- `recordID` - Primary key
- `patientID` - Foreign key to Patient
- `height` - Patient height (decimal)
- `weight` - Patient weight (decimal) 
- `allergies` - Patient allergies (text)
- `createdAt` - Record creation timestamp
- `lastVisit` - Date of last visit

**Key Methods:**
- `createForPatient($patientID)` - Create empty record for patient
- `findByPatientID($patientID)` - Find record by patient ID
- `update()` - Update existing record
- `updateLastVisit($patientID, $visitDate)` - Update last visit date

### AppointmentReport Model
Located: `app/models/AppointmentReport.php`

**Properties:**
- `appointmentReportID` - Primary key
- `patientRecordID` - Foreign key to PatientRecord
- `appointmentID` - Foreign key to Appointment  
- `bloodPressure` - Blood pressure reading (varchar)
- `pulseRate` - Pulse rate (integer)
- `temperature` - Body temperature (decimal)
- `respiratoryRate` - Respiratory rate (integer)
- `generalAppearance` - General appearance notes (text)
- `createdAt` - Report creation timestamp

**Key Methods:**
- `createForAppointment($appointmentID, $patientRecordID)` - Create empty report
- `findByAppointmentID($appointmentID)` - Find report by appointment ID
- `update()` - Update existing report
- `getReportByAppointmentID($appointmentID)` - Get full report with patient data

## Usage Examples

### In Controllers

```php
// Get patient's medical record
require_once 'app/models/PatientRecord.php';
$patientRecord = new PatientRecord($conn);
if ($patientRecord->findByPatientID($patientID)) {
    $height = $patientRecord->height;
    $weight = $patientRecord->weight;
    $allergies = $patientRecord->allergies;
}

// Get appointment report
require_once 'app/models/AppointmentReport.php';
$appointmentReport = new AppointmentReport($conn);
$report = $appointmentReport->getReportByAppointmentID($appointmentID);
if ($report) {
    $bloodPressure = $report['BloodPressure'];
    $temperature = $report['Temperature'];
}

// Update patient record
$patientRecord->height = 175.5;
$patientRecord->weight = 70.2;
$patientRecord->allergies = "Penicillin, Nuts";
$patientRecord->update();

// Update appointment report
$appointmentReport->bloodPressure = "120/80";
$appointmentReport->pulseRate = 72;
$appointmentReport->temperature = 36.5;
$appointmentReport->respiratoryRate = 16;
$appointmentReport->generalAppearance = "Patient appears healthy";
$appointmentReport->update();
```

### Database Setup

1. **Using SQL Triggers (Recommended):**
   Run the setup script: `storage/setup_appointment_system.sql`
   This will:
   - Create PatientRecords for existing patients
   - Create AppointmentReports for existing appointments
   - Set up automatic triggers for future records

2. **Using Application Logic:**
   The models are already updated to automatically create records:
   - `Patient::createPatient()` now creates a PatientRecord
   - `Appointment::create()` now creates an AppointmentReport

## Benefits

1. **Automatic Record Creation:** No need to manually create medical records
2. **Data Consistency:** Every patient has a medical record, every appointment has a report
3. **Easy Access:** Simple methods to retrieve and update medical data
4. **Referential Integrity:** Proper foreign key relationships maintained

## Important Notes

- PatientRecords are created with NULL values for medical data initially
- AppointmentReports are created with NULL values for vital signs initially
- Doctors can update these records with actual medical data during appointments
- The system maintains referential integrity - deleting appointments also removes their reports
- Use the SQL setup script to migrate existing data and set up triggers

## Future Enhancements

These models provide the foundation for:
- Medical history tracking
- Vital signs monitoring
- Treatment planning
- Doctor notes and prescriptions
- Patient health analytics 