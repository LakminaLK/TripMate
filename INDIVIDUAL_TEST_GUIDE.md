# TripMate Individual Test Cases Guide

This guide provides commands to run each test case separately for screenshot documentation.

## 🎯 Individual Test Case Commands

### **ATC01 - Login Form Rendering**
```bash
php artisan test tests/Feature/ATC01_LoginFormTest.php
```
**What it tests:** Login form renders with username, password, Sign In button
**Screenshot focus:** Admin and Hotel login forms

---

### **ATC02 - Invalid Credentials Error**
```bash
php artisan test tests/Feature/ATC02_InvalidCredentialsTest.php
```
**What it tests:** Wrong credentials show error messages
**Screenshot focus:** Error handling and validation

---

### **ATC03 - Admin Login Redirect**
```bash
php artisan test tests/Feature/ATC03_AdminLoginTest.php
```
**What it tests:** Admin login redirects to dashboard
**Screenshot focus:** Admin login flow and redirection

---

### **ATC04 - Traveler Login Redirect**
```bash
php artisan test tests/Feature/ATC04_TravelerLoginTest.php
```
**What it tests:** Traveler login redirects to user dashboard
**Screenshot focus:** Tourist/Traveler login flow

---

### **ATC05 - Logout Functionality**
```bash
php artisan test tests/Feature/ATC05_LogoutTest.php
```
**What it tests:** Logout button redirects to login page
**Screenshot focus:** Logout process and redirection

---

### **ATC06 - Navbar Links**
```bash
php artisan test tests/Feature/ATC06_NavbarLinksTest.php
```
**What it tests:** Navbar buttons work and navigate correctly
**Screenshot focus:** Navigation functionality

---

### **ATC07 - Hotel List Rendering**
```bash
php artisan test tests/Feature/ATC07_HotelListTest.php
```
**What it tests:** Hotels load with name, rating, map
**Screenshot focus:** Hotel listing page

---

### **ATC08 - Hotel Details Page**
```bash
php artisan test tests/Feature/ATC08_HotelDetailsTest.php
```
**What it tests:** Hotel details show info and rooms
**Screenshot focus:** Hotel details page

---

## 🚀 Quick Commands

### Run All Test Cases at Once:
```bash
php artisan test tests/Feature/ATC01_LoginFormTest.php tests/Feature/ATC02_InvalidCredentialsTest.php tests/Feature/ATC03_AdminLoginTest.php tests/Feature/ATC04_TravelerLoginTest.php tests/Feature/ATC05_LogoutTest.php tests/Feature/ATC06_NavbarLinksTest.php tests/Feature/ATC07_HotelListTest.php tests/Feature/ATC08_HotelDetailsTest.php
```

### Test Specific Groups:
```bash
# Authentication Tests (ATC01-ATC05)
php artisan test tests/Feature/ATC01_LoginFormTest.php tests/Feature/ATC02_InvalidCredentialsTest.php tests/Feature/ATC03_AdminLoginTest.php tests/Feature/ATC04_TravelerLoginTest.php tests/Feature/ATC05_LogoutTest.php

# Navigation & UI Tests (ATC06-ATC08)
php artisan test tests/Feature/ATC06_NavbarLinksTest.php tests/Feature/ATC07_HotelListTest.php tests/Feature/ATC08_HotelDetailsTest.php
```

## 📸 Screenshot Documentation Process

For each test case:

1. **Run the test command**
2. **Take screenshot of terminal output**
3. **Document the results** in your test report
4. **Note any specific behaviors** observed

## 📋 Test Case Files Summary

| Test Case | File Name | Focus Area |
|-----------|-----------|------------|
| ATC01 | `ATC01_LoginFormTest.php` | Login form rendering |
| ATC02 | `ATC02_InvalidCredentialsTest.php` | Error handling |
| ATC03 | `ATC03_AdminLoginTest.php` | Admin authentication |
| ATC04 | `ATC04_TravelerLoginTest.php` | Traveler authentication |
| ATC05 | `ATC05_LogoutTest.php` | Logout functionality |
| ATC06 | `ATC06_NavbarLinksTest.php` | Navigation |
| ATC07 | `ATC07_HotelListTest.php` | Hotel listings |
| ATC08 | `ATC08_HotelDetailsTest.php` | Hotel details |

## ✅ Expected Results

All test cases should **PASS** with status indicators:
- ✅ Green checkmarks for passing tests
- Test execution times
- Number of assertions verified

---

*Use these commands to run individual test cases and capture screenshots for your automated testing documentation.*