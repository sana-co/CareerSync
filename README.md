# 💼 CareerSync – Career & Internship Management System

**Project:** CareerSync  
**Type:** Web-based System  
**Domain:** Career & Internship Management  
**Group:** CS-02  

---

## 📌 Overview

CareerSync is a centralized web-based platform designed to streamline and digitize the entire career and internship management lifecycle. It connects students, career counselors, company HR representatives, validators, and administrators into a single integrated system.

### 🎯 Objectives

- Provide a centralized platform for job/internship applications
- Enable career counseling and scheduling
- Allow companies to manage recruitment efficiently
- Implement structured validation workflows
- Improve transparency and process tracking

---

## ⚠️ Current System Limitations

- Lack of centralized data management  
- Poor application tracking  
- Fragmented communication  
- Manual and inefficient validation processes  
- Limited access to career guidance  

---

## 🚀 Features

### 👤 User Roles

- Student / Job-seeker  
- Career Counselor  
- Company HR  
- Validation Team  
- Admin  

---

### 🔑 Core Functionalities

- User authentication with role-based access  
- Profile management  
- Job posting and application system  
- Application lifecycle tracking  
- Interview scheduling  
- Document validation  
- Notification system  
- Counseling module  
- Admin dashboard  

---

## 🧱 System Architecture

CareerSync follows the **MVC (Model–View–Controller)** architecture.

### 🧩 Model

Handles data and database interactions:

- CRUD operations  
- Data validation  
- MySQL database integration  

Examples:
- `User.php`
- `jobPost.php`
- `candidate.php`
- `company.php`
- `interview.php`

---

### 🖥️ View

Handles UI/UX:

- Built using HTML, CSS, JavaScript  
- Responsive design  
- Role-based dashboards  

Examples:
- Candidate dashboard  
- Company dashboard  
- Admin dashboard  
- Validator interface  

---

### 🔄 Controller

Acts as the system logic handler:

- Processes requests  
- Manages sessions  
- Connects Model and View  

Examples:
- `candidateDash_controller.php`
- `companyDash_controller.php`
- `adminDash_controller.php`

---

## 🔄 System Workflow

1. User interacts with UI  
2. Request sent to Controller  
3. Controller calls Model  
4. Model interacts with database  
5. Data returned to Controller  
6. Controller updates View  

---

## 🧠 System Design

### 👥 Use Case Highlights

- Register/Login  
- Manage profile  
- Apply for jobs  
- Post job listings  
- Schedule interviews  
- Validate documents  
- Generate reports  

---

### 🏗️ Class Structure

Main Classes:
- User (Base class)
- Candidate
- Company
- Counselor
- Validator
- Admin
- JobPost
- Application
- CV
- Interview

---

### 🗄️ Database Entities

- Users  
- Candidates  
- Companies  
- Admin  
- Validators  
- Counselors  
- JobPosts  
- Applications  
- Interviews  
- Consultation  
- Messages  
- CVTable  

---

## ⚙️ Feasibility

### 🔧 Technical
- HTML, CSS, JS frontend  
- PHP backend  
- MySQL database  

### 🧑‍💻 Operational
- Easy to use  
- Minimal training required  

### 💰 Economic
- Uses open-source technologies  
- Low deployment cost  

### ⏱️ Schedule
- Completed within academic timeline  

---

## 📋 Requirements

### ✔️ Functional Requirements

- User & profile management  
- Job/internship system  
- Application tracking  
- Interview scheduling  
- Validation workflows  
- Notifications  
- Counseling system  

### ⚡ Non-Functional Requirements

- Security (authentication, hashing)  
- Scalability  
- Performance optimization  
- Reliability  
- Maintainability  

---

## 📦 Scope

### ✅ In Scope
- Full system implementation  
- Backend + frontend integration  
- Role-based dashboards  
- Notification system  

### ❌ Out of Scope
- Third-party integrations  
- Mobile app  
- AI-based recommendations  

---

## ⚠️ Constraints

- Web-based only  
- Depends on internet connection  
- Limited scalability without infrastructure upgrade  

---

## ✅ Completed Features

- Authentication system  
- Role-based dashboards  
- Job posting & application system  
- Interview scheduling  
- Messaging system  
- Validation system  
- Report generation  
- Notification system  
- Feedback system  

---

## ⏳ Remaining Work

- Deploy system to a live server (currently local)

---

## 👥 Team Contributions

### 🧑‍💻 Anuk Thotawatta
- Admin dashboard  
- System logs  
- Job filters  
- Validation system  
- Payment gateway  

### 🧑‍💻 Sanuth Wijayarathna
- Company dashboard  
- Job management  
- Notifications  
- Interview filtering  

### 🧑‍💻 Hiruni Ravindya
- Candidate dashboard  
- Registration system  
- Job description module  

### 🧑‍💻 Saraniya Thavakumar
- Contact system  
- Email integration  
- Counseling module  
- Feedback system  

---

## 🧪 Testing

- Comprehensive test cases implemented  
- Covers:
  - Authentication  
  - Job filtering  
  - Payment system  
  - Validation workflows  
  - Dashboard features  

All major features passed test scenarios.

---

## ⚡ Key Features Summary

- Multi-role system  
- Full recruitment lifecycle management  
- Secure authentication  
- Real-time notifications  
- Modular MVC architecture  

---

## 🧠 Future Improvements

- Deploy to production server  
- Add mobile application  
- Integrate third-party APIs  
- Implement AI recommendations  

---

## 📚 Reference

Source document: :contentReference[oaicite:0]{index=0}  

---

## 🏁 Conclusion

CareerSync successfully delivers a scalable, efficient, and centralized solution for managing career and internship processes, improving coordination, transparency, and user experience across all stakeholders.

---
